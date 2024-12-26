<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WithdrawAccount;
use App\Models\WithdrawalSchedule;
use App\Models\WithdrawMethod;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Traits\Payment;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Txn;
use App\Facades\Txn\ZendryTxn;
use Validator;
use App\Models\Gateway;

class WithdrawController extends Controller
{
    use ImageUpload, NotifyTrait, Payment;

    private $zendryTxn;

    public function __construct()
    {
        $this->zendryTxn = new ZendryTxn();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = WithdrawAccount::where('user_id', auth()->id())->get();

        return view('frontend::withdraw.account.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação básica para o método de retirada
        if ($request->withdraw_method_id == 3 || $request->withdraw_method_id == 5) {
            $validator = Validator::make($request->all(), [
                'withdraw_method_id' => 'required',
                'tipo' => 'required',
                'chave' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'withdraw_method_id' => 'required',
                'method_name' => 'required',
            ]);
        }
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $userId = auth()->id();
        $withdrawMethodId = $request->withdraw_method_id;
        $methodName = ($withdrawMethodId != 3 && $withdrawMethodId != 5) ? $request->method_name : 'PIX Automático';

        // Preparar dados de credenciais
        $credentials = $this->prepareCredentials($request, $withdrawMethodId);

        // Salvar a conta de retirada
        WithdrawAccount::create([
            'user_id' => $userId,
            'withdraw_method_id' => $withdrawMethodId,
            'method_name' => $methodName,
            'credentials' => json_encode($credentials),
        ]);

        notify()->success('Criado com sucesso', 'success');
        return redirect()->route('user.withdraw.account.index');
    }

    // Método para preparar as credenciais com base no método de retirada
    private function prepareCredentials(Request $request, $withdrawMethodId)
    {
        if ($withdrawMethodId == 3 || $request->withdraw_method_id == 5) {
            // Unir tipo e chave PIX
            return [
                'Chave PIX' => [
                    'type' => $request->tipo,
                    'validation' => 'required',
                    'value' => $request->chave,
                ]
            ];
        }

        // Caso não seja o método 3
        return $request->credentials ?? [];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $withdrawMethods = WithdrawMethod::where('status', true)->get();

        return view('frontend::withdraw.account.create', compact('withdrawMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $withdrawMethods = WithdrawMethod::all();
        $withdrawAccount = WithdrawAccount::find($id);

        return view('frontend::withdraw.account.edit', compact('withdrawMethods', 'withdrawAccount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $withdrawAccount = WithdrawAccount::find($id);

        $oldCredentials = json_decode($withdrawAccount->credentials, true);

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (!isset($value['value'])) {
                $credentials[$key]['value'] = $oldCredentials[$key]['value'];
            }

            if (isset($value['value']) && is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], $oldCredentials[$key]['value']);
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        $withdrawAccount->update($data);
        notify()->success('Atualizado com sucesso', 'success');

        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * @return string
     */
    public function withdrawMethod($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);

        if ($withdrawMethod) {
            return view('frontend::withdraw.include.__account', compact('withdrawMethod'))->render();
        }

        return '';
    }

    /**
     * @return array
     */
    public function details($accountId, int $amount = 0)
    {

        $withdrawAccount = WithdrawAccount::find($accountId);

        $credentials = json_decode($withdrawAccount->credentials, true);

        $currency = setting('site_currency', 'global');
        $method = $withdrawAccount->method;
        $charge = $method->charge;
        $name = $withdrawAccount->method_name;
        $processingTime = (int) $method->required_time > 0 ? 'Em até: ' . $withdrawAccount->method->required_time . $withdrawAccount->method->required_time_format : 'Automático';

        $info = [
            'name' => $name,
            'charge' => $charge,
            'charge_type' => $withdrawAccount->method->charge_type,
            'range' => 'Mínimo ' . $method->min_withdraw . ' ' . $currency . ' e ' . 'máximo ' . $method->max_withdraw . ' ' . $currency,
            'processing_time' => $processingTime,
        ];

        if ($withdrawAccount->method->charge_type != 'fixed') {
            $charge = ($charge / 100) * $amount;
        }

        $html = view('frontend::withdraw.include.__details', compact('credentials', 'name', 'charge'))->render();

        return [
            'html' => $html,
            'info' => $info,
        ];
    }

    /**
     * @return string
     */
    public function withdrawNow(Request $request)
    {
        if (!setting('user_withdraw', 'permission') || !\Auth::user()->withdraw_status) {
            abort('403', __('Withdraw Disable Now'));
        }

        $withdrawOffDays = WithdrawalSchedule::where('status', 0)->pluck('name')->toArray();
        $date = Carbon::now();
        $today = $date->format('l');

        if (in_array($today, $withdrawOffDays)) {
            abort('403', __('Limite de saque diário atingido'));
        }

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
            'withdraw_account' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        //daily limit
        $todayTransaction = Transaction::where('type', TxnType::Withdraw)->orWhere('type', TxnType::WithdrawAuto)->whereDate('created_at', Carbon::today())->count();
        $dayLimit = (float) Setting('withdraw_day_limit', 'fee');
        if ($todayTransaction >= $dayLimit) {
            notify()->error(__('Limite de saque alcançado'), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
        $amount = (float) $input['amount'];

        $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
        $withdrawMethod = $withdrawAccount->method;

        if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'O mínimo é de ' . $currencySymbol . $withdrawMethod->min_withdraw . ' até ' . $currencySymbol . $withdrawMethod->max_withdraw;
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        $charge = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
        $totalAmount = $amount - (float) $charge;

        $user = Auth::user();

        $carteira = $request->carteira;

        if ($user->$carteira < $amount) {
            notify()->error(__('Saldo insuficiente, por favor mova fundos entre as carteiras para processar o saque'), 'Error');

            return redirect()->back();
        }


        $user->decrement($carteira, $amount);

        $payAmount = $amount * $withdrawMethod->rate;

        $type = $withdrawMethod->type == 'auto' ? TxnType::WithdrawAuto : TxnType::Withdraw;

        $txnInfo = Txn::new(
            $input['amount'],
            $charge,
            $totalAmount,
            $withdrawMethod->name,
            'Saque com' . $withdrawAccount->method_name,
            $type,
            TxnStatus::Pending,
            $withdrawMethod->currency,
            $payAmount,
            $user->id,
            null,
            'User',
            json_decode($withdrawAccount->credentials, true)
        );

        if ($withdrawMethod->type == 'auto' && $withdrawMethod->id != 3 && $withdrawMethod->id != 5) {
            $gatewayCode = $withdrawMethod->gateway->gateway_code;

            return self::withdrawAutoGateway($gatewayCode, $txnInfo);
        }

        $symbol = setting('currency_symbol', 'global');
        $notify = [
            'card-header' => 'Pedido de saque',
            'title' => $symbol . $txnInfo->amount . ' processado com sucesso',
            'p' => 'Seu pedido de saque foi enviado',
            'strong' => 'Transação ID: ' . $txnInfo->tnx,
            'action' => route('user.withdraw.view'),
            'a' => 'SACAR NOVAMENTE',
            'view_name' => 'withdraw',
        ];
        Session::put('user_notify', $notify);
        $shortcodes = [
            '[[full_name]]' => $txnInfo->user->full_name,
            '[[txn]]' => $txnInfo->tnx,
            '[[method_name]]' => $withdrawMethod->name,
            '[[withdraw_amount]]' => $txnInfo->amount . setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify(setting('site_email', 'global'), 'withdraw_request', $shortcodes);
        $this->pushNotify('withdraw_request', $shortcodes, route('admin.withdraw.pending'), $user->id);
        $this->smsNotify('withdraw_request', $shortcodes, $user->phone);

        // Chamos a SuitPay 
        if ($withdrawMethod->id == 3) {
            return $this->suitpay($withdrawAccount, $txnInfo, $totalAmount, $withdrawAccount, $notify);
        } elseif ($withdrawMethod->id == 5) {

            $result = $this->zendryWhitdraw($txnInfo, $totalAmount, $withdrawAccount, $notify);

             // Exibindo o resultado
             if ($result['success']) {
                // Atualizar status da transação
                $txnInfo->status = 'success';
                $txnInfo->save();

                return view('frontend::withdraw.success', compact('notify'));
            } else {
                // Atualizar status da transação
                $txnInfo->status = 'pending';
                $txnInfo->save();
                return view('frontend::withdraw.success', compact('notify'));
            }
        }

        return redirect()->route('user.notify');
    }

    /** 
     * @valor retrn valor 
     * @param // *valor
     * @param // *tarifas
     */
    public function suitpay($formData, $transacao, $valor, $saque, $notify)
    {
        // Dados SuitPay 
        $gateway = Gateway::where('gateway_code', 'suitpay')->first();
        $credentials = json_decode($gateway->credentials, true);
        $key = $credentials['api_key'];
        $ci = $credentials['ci'];

        // Info do usuário 
        $dados = json_decode($formData->credentials, true);
        $tipo = $dados['Chave PIX']['type'];
        $chave = $dados['Chave PIX']['value'];
        // Pesquisar a transação
        if ($transacao) {
            $name = $tipo;
            $value = $chave;
            if ($name === 'Chave PIX') {
                $chavePix = $value;
            } elseif ($name === 'Selecione a Chave PIX') {
                switch ($value) {
                    case 'CPF':
                        $tipoChavePix = 'document';
                        break;
                    case 'E-mail':
                        $tipoChavePix = 'email';
                        break;
                    case 'Telefone':
                        $tipoChavePix = 'phoneNumber';
                        break;
                    case 'aleatoria':
                        $tipoChavePix = 'randomKey';
                        break;
                    default:
                        break;
                }
            }
            // Configurações da solicitação
            $url = 'https://ws.suitpay.app/api/v1/gateway/pix-payment';
            $headers = [
                'ci:' . $ci,
                'cs:' . $key,
                'Content-Type: application/json'
            ];
            $data = [
                "value" => $valor,
                "key" => $chavePix ?? null,
                "typeKey" => $tipoChavePix ?? null,
            ];

            // Fazendo a solicitação e depurando o resultado
            $result = $this->makeRequest($url, $headers, $data);

            // Exibindo o resultado
            if ($result['success']) {
                // Atualizar status da transação
                $transacao->status = 'success';
                $transacao->save();

                return view('frontend::withdraw.success', compact('notify'));
            } else {
                // Atualizar status da transação
                $transacao->status = 'pending';
                $transacao->save();
                return view('frontend::withdraw.success', compact('notify'));
            }
        }
    }

    public function zendryWhitdraw($txnInfo, $totalAmount, $withdrawAccount, $notify)
    {
        $user = auth()->user();

        $response = $this->zendryTxn->createWhitdraw($txnInfo, $totalAmount, $withdrawAccount);

        if ($response['status'] == 'error') {
            notify()->error($response['message'], 'Error');
            return redirect()->back()->with('error', $response['message']);
        }

        return view('frontend::withdraw.success', compact('notify'));
    }

    private function makeRequest($url, $headers, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'error' => $error];
        } else {
            return ['success' => true, 'response' => $response];
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function withdraw()
    {
        $accounts = WithdrawAccount::where('user_id', \Auth::id())->get();
        $accounts = $accounts->reject(function ($value, $key) {
            return !$value->method->status;
        });
        // User 
        $user = \Auth::user();

        return view('frontend::withdraw.now', compact('accounts', 'user'));
    }

    /* Delete */
    public function delete($id)
    {
        $withdrawAccount = WithdrawAccount::find($id);
        $withdrawAccount->delete();

        notify()->success('Removido com sucesso', 'success');

        return redirect()->route('user.withdraw.account.index');
    }

    public function withdrawLog()
    {
        $withdraws = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->where('type', TxnType::Withdraw)
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                });
        })->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::withdraw.log', compact('withdraws'));
    }
}
