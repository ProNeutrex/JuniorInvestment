<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\InvestStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\DepositMethod;
use App\Models\Invest;
use App\Models\LevelReferral;
use App\Models\Schema;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Txn;

class InvestController extends GatewayController
{
    use ImageUpload, NotifyTrait;

    public function investNow(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'schema_id' => 'required',
            'invest_amount' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'wallet' => 'in:main,profit,gateway',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $user = Auth::user();
        $schema = Schema::with('schedule')->find($input['schema_id']);

        $investAmount = $input['invest_amount'];

        //Insufficient Balance validation
        if ($input['wallet'] == 'main' && $user->balance < $investAmount) {
            notify()->error('Insufficient Balance Your Main Wallet', 'Error');

            return redirect()->route('user.schema.preview', $schema->id);
        } elseif ($input['wallet'] == 'profit' && $user->profit_balance < $investAmount) {
            notify()->error('Seu saldo é insuficiente, faça uma recarga', 'Error');

            return redirect()->route('user.schema.preview', $schema->id);
        }

        //invalid Amount
        if (($schema->type == 'range' && ($schema->min_amount > $investAmount || $schema->max_amount < $investAmount)) || ($schema->type == 'fixed' && $schema->fixed_amount != $investAmount)) {
            notify()->error('Invest Amount Out Of Range', 'Error');

            return redirect()->route('user.schema.preview', $schema->id);
        }

        $periodHours = $schema->schedule->time;
        $nextProfitTime = Carbon::now()->addHour($periodHours);
        $siteName = setting('site_title', 'global');
        $data = [
            'user_id' => $user->id,
            'schema_id' => $schema->id,
            'invest_amount' => $investAmount,
            'next_profit_time' => $nextProfitTime,
            'capital_back' => $schema->capital_back,
            'interest' => $schema->return_interest,
            'interest_type' => $schema->interest_type,
            'return_type' => $schema->return_type,
            'number_of_period' => $schema->number_of_period,
            'period_hours' => $periodHours,
            'wallet' => $input['wallet'],
            'status' => InvestStatus::Ongoing,
        ];

        if ($input['wallet'] == 'main') {
            $user->decrement('balance', $input['invest_amount']);

        } elseif ($input['wallet'] == 'profit') {
            $user->decrement('profit_balance', $input['invest_amount']);
        } else {

            $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();

            $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $investAmount) : $gatewayInfo->charge;
            $finalAmount = (float) $investAmount + (float) $charge;
            $payAmount = $finalAmount * $gatewayInfo->rate;
            $payCurrency = $gatewayInfo->currency;

            $manualData = null;
            if (isset($input['manual_data'])) {
                $manualData = $input['manual_data'];
                foreach ($manualData as $key => $value) {

                    if (is_file($value)) {
                        $manualData[$key] = self::imageUploadTrait($value);
                    }
                }

            }

            $txnInfo = Txn::new($investAmount, $charge, $finalAmount, $gatewayInfo->name, $schema->name.' Invested', TxnType::Investment, TxnStatus::Pending, $payCurrency, $payAmount, $user->id, null, 'user', $manualData ?? []);
            $data = array_merge($data, ['status' => InvestStatus::Pending, 'transaction_id' => $txnInfo->id]);
            Invest::create($data);

            return self::depositAutoGateway($input['gateway_code'], $txnInfo);

        }

        $tnxInfo = Txn::new($input['invest_amount'], 0, $input['invest_amount'], 'system', $schema->name.' Plan Invested', TxnType::Investment, TxnStatus::Success, null, null, $user->id);
        $data = array_merge($data, ['transaction_id' => $tnxInfo->id]);
        Invest::create($data);

        if (setting('site_referral', 'global') == 'level' && setting('investment_level')) {
            $level = LevelReferral::where('type', 'investment')->max('the_order') + 1;
            creditReferralBonus($user, 'investment', $input['invest_amount'], $level);
        }

        $shortcodes = [
            '[[full_name]]' => $tnxInfo->user->full_name,
            '[[txn]]' => $tnxInfo->tnx,
            '[[plan_name]]' => $tnxInfo->invest->schema->name,
            '[[invest_amount]]' => $tnxInfo->amount.setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($tnxInfo->user->email, 'user_investment', $shortcodes);
        $this->pushNotify('user_investment', $shortcodes, route('user.invest-logs'), $tnxInfo->user->id);
        $this->smsNotify('user_investment', $shortcodes, $tnxInfo->user->phone);

        notify()->success('Investimento concluído com sucesso', 'success');

        return redirect()->route('user.invest-logs');
    }

    public function investLogs(Request $request)
    {

        if ($request->ajax()) {
            $data = Invest::with('schema')->where('user_id', auth()->id())->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', 'frontend::user.include.__invest_icon')
                ->addColumn('schema', 'frontend::user.include.__invest_schema')
                ->addColumn('rio', 'frontend::user.include.__invest_rio')
                ->addColumn('profit', 'frontend::user.include.__invest_profit')
                ->addColumn('period_remaining', function ($raw) {
                    if ($raw->return_type != 'period') {
                        return 'Unlimited';
                    }

                    return $raw->number_of_period.($raw->number_of_period < 2 ? ' Time' : ' Times');
                })
                ->editColumn('capital_back', 'frontend::user.include.__invest_capital_back')
                ->editColumn('next_profit_time', 'frontend::user.include.__invest_next_profit_time')
                ->rawColumns(['icon', 'schema', 'rio', 'profit', 'capital_back', 'next_profit_time'])
                ->make(true);
        }

        return view('frontend::user.invest.log');
    }

    public function investCancel($id)
    {
        $investment = Invest::find($id);

        //daily limit
        $todayTransaction = Invest::where('status', InvestStatus::Canceled)->whereDate('created_at', Carbon::today())->count();
        $dayLimit = (float) Setting('send_money_day_limit', 'fee');
        if ($todayTransaction >= $dayLimit) {
            notify()->error(__('Today Investment Cancel limit has been reached'), 'Error');

            return redirect()->back();
        }

        if ($investment->is_cancel && $investment->status == InvestStatus::Ongoing) {
            $investment->update([
                'status' => InvestStatus::Canceled,
            ]);

            $user = $investment->user;

            $user->balance += $investment->invest_amount;
            $user->save();

            Txn::new($investment->invest_amount, 0, $investment->invest_amount, 'system', $investment->schema->name.' '.'Saldo retornado para minha conta', TxnType::Refund, TxnStatus::Success, null, null, $user->id);
            notify()->success('Investimento cancelado', 'success');

            return redirect()->route('user.invest-logs');
        }
        abort_if(! $investment->schema->schema_cancel, 403, 'Can Not Be Cancel Investment');
        notify()->warning('Can Not Be Cancel Investment', 'warning');

        return redirect()->route('user.invest-logs');
    }
}
