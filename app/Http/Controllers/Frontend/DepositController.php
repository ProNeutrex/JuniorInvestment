<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\DepositMethod;
use App\Models\Gateway;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Txn;
use App\Facades\Txn\ZendryTxn;
use Validator;
use Illuminate\Support\Facades\Log;

class DepositController extends GatewayController
{
    use ImageUpload, NotifyTrait;
    private $zendryTxn;

    public function __construct()
    {
        $this->zendryTxn = new ZendryTxn();
    }

    public function deposit()
    {

        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }

        $isStepOne = 'current';
        $isStepTwo = '';
        $gateways = DepositMethod::where('status', 1)->get();

        return view('frontend::deposit.now', compact('isStepOne', 'isStepTwo', 'gateways'));
    }

    public function depositNow(Request $request)
    {
        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }

        $validator = Validator::make($request->all(), [
            'gateway_code' => 'required',
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
        $amount = $input['amount'];

        if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Deposit the Amount within the range ' . $currencySymbol . $gatewayInfo->minimum_deposit . ' to ' . $currencySymbol . $gatewayInfo->maximum_deposit;
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
        $finalAmount = (float) $amount + (float) $charge;
        $payAmount = $finalAmount * $gatewayInfo->rate;
        $depositType = TxnType::Deposit;

        if (isset($input['manual_data'])) {

            $depositType = TxnType::ManualDeposit;
            $manualData = $input['manual_data'];

            foreach ($manualData as $key => $value) {

                if (is_file($value)) {
                    $manualData[$key] = self::imageUploadTrait($value);
                }
            }
        }

        $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code, 'Depósito via ' . $gatewayInfo->name, $depositType, TxnStatus::Pending, $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User', $manualData ?? []);

        if ($txnInfo->method == 'Suitpay-brl') {
            // Vamos chamar a SuitPay 
            $suit = $this->suitpay($txnInfo, $gatewayInfo);
            return $suit;
        } else if ($txnInfo->method == 'Zendry') {
            // Vamos chamar a SuitPay 
            $suit = $this->zendry($txnInfo, $gatewayInfo);
            return $suit;
        } else {
            return self::depositAutoGateway($gatewayInfo->gateway_code, $txnInfo);
        }
    }

    public function depositLog()
    {
        $deposits = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                })
                ->whereIn('type', [TxnType::Deposit, TxnType::ManualDeposit]);
        })->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::deposit.log', compact('deposits'));
    }

    /**
     * @param $valor 
     * @param $gateway
     */
    public function suitpay($info, $gatewayInfo)
    {
        $user = auth()->user();
        $suitpay = Gateway::find($gatewayInfo->gateway_id);
        $credentials = json_decode($suitpay->credentials, true);
        $key = $credentials['api_key'];
        $ci = $credentials['ci'];

        // URL da API
        $url = 'https://sandbox.ws.suitpay.app/api/v1/gateway/request-qrcode';

        // Cabeçalhos da requisição
        $headers = [
            'ci:' . $ci,
            'cs:' . $key,
            'Content-Type: application/json'
        ];

        // Dados do corpo da requisição
        $data = [
            "requestNumber" => $info->tnx,
            "dueDate" => "2022-10-30",
            "amount" => $info->pay_amount,
            "shippingAmount" => 0.0,
            "discountAmount" => 0.0,
            "usernameCheckout" => "checkout",
            "callbackUrl" => route('user.deposit.confirmar.suitpay'),
            "client" => [
                "name" => $user->first_name,
                $user->last_name,
                "document" => "927.300.300-18",
                "phoneNumber" => $user->phone,
                "email" => $user->email,

            ],
            "products" => [
                [
                    "description" => $info->description,
                    "quantity" => 1,
                    "value" => $info->amount
                ]
            ]
        ];

        // Inicializar cURL
        $curl = curl_init();

        // Configurar opções do cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);

        // Executar a requisição e capturar a resposta
        $response = curl_exec($curl);

        // Verificar se ocorreu erro
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            echo "Erro: $error_msg";
            return;
        }

        // Fechar cURL
        curl_close($curl);

        // Decodificar a resposta JSON
        $decodedResponse = json_decode($response, true);

        // Verificar se a resposta contém o paymentCode e paymentCodeBase64
        if (isset($decodedResponse['paymentCode']) && isset($decodedResponse['paymentCodeBase64'])) {
            $paymentCode = $decodedResponse['paymentCode'];
            $paymentCodeBase64 = $decodedResponse['paymentCodeBase64'];
            // Exibir o paymentCode e o QR code em base64
            return view('frontend.money_invest.deposit.suitpay', compact('paymentCode', 'paymentCodeBase64'));
        } else {
            notify()->error('Não foi possível efetuar o depósito agora, tente novamente mais tarde', 'Error');
            return redirect()->back()->with('error', 'Não foi possível efetuar o depósito agora, tente novamente mais tarde');
        }
    }

    /**
     * @param $valor 
     * @param $gateway
     */
    public function zendry($info, $gatewayInfo)
    {
        $user = auth()->user();

        $response = $this->zendryTxn->createPayment($info, $gatewayInfo);

        if ($response['status'] == 'error') {
            notify()->error($response['message'], 'Error');
            return redirect()->back()->with('error', $response['message']);
        }

        $paymentCode = $response['message']['paymentCode'];
        $paymentCodeBase64 = $response['message']['paymentCodeBase64'];

        return view('frontend.money_invest.deposit.suitpay', compact('paymentCode', 'paymentCodeBase64'));
    }

    /**
     * @param $TransaçãoID
     * @param $status
     */
    // Método para processar o webhook de PIX
    public function confirmarSuitpay(Request $request)
    {
        // Obter o ClientSecret (cs) do gateway configurado
        $gateway = Gateway::where('gateway_code', 'suitpay')->first();
        $clientSecret = json_decode($gateway->credentials, true)['cs'];

        // Dados recebidos no webhook
        $data = $request->all();

        // Validação do hash
        $hashString = $data['idTransaction'] . $data['typeTransaction'] . $data['statusTransaction'] . $data['value'] . $data['payerName'] . $data['payerTaxId'] . $data['paymentDate'] . $data['paymentCode'] . $data['requestNumber'];
        $expectedHash = hash('sha256', $hashString . $clientSecret);

        if ($expectedHash !== $data['hash']) {
            // Se o hash não for válido, rejeitar a requisição
            Log::error('Hash inválido no webhook de PIX', ['data' => $data]);
            return response()->json(['error' => 'Invalid hash'], 400);
        }

        // Verificar se a transação existe com o `requestNumber`
        $transaction = Transaction::where('tnx', $data['idTransaction'])->first();

        if (!$transaction) {
            // Se a transação não for encontrada, registrar o erro
            Log::error('Transação não encontrada', ['requestNumber' => $data['requestNumber']]);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Atualizar o status da transação com base no status recebido
        if ($data['statusTransaction'] === 'PAID_OUT') {
            $transaction->status = 'success';
            $responseMessage = 'Transaction updated successfully';
        } elseif ($data['statusTransaction'] === 'CHARGEBACK') {
            $transaction->status = 'chargeback';
            $responseMessage = 'Transaction marked as chargeback';
        } else {
            $responseMessage = 'Transaction status updated but no change in status';
        }

        // Atualizar saldo do usuário
        $user = User::find($transaction->user_id);
        if ($user) {
            $user->balance += $transaction->amount;
            $user->save();
        }

        // Salvar transação 
        $transaction->save();

        // Verificar se a requisição é um webhook ou AJAX
        if ($request->is('api/*')) {
            return response()->json(['message' => $responseMessage], 200);
        }

        // Se não for uma requisição API (possivelmente uma requisição AJAX)
        return response()->json(['message' => $responseMessage], 200);
    }

    public function confirmarZendry(Request $request)
    {
        $this->zendryTxn->confirmQrCodePayment($request);
    }
}
