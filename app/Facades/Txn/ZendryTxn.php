<?php

namespace App\Facades\Txn;

use App\Models\User;
use App\Models\Gateway;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZendryTxn
{

    public function __construct()
    {
    }

    public function confirmQrCodePayment(Request $request)
    {
        // Obter o ClientSecret (cs) do gateway configurado
        $gateway = Gateway::where('gateway_code', 'zendry')->first();
        // $clientSecret = json_decode($gateway->credentials, true)['cs'];

        // Dados recebidos no webhook
        $data = $request->all();

        // Será que necessita de validar o hash ou só o header ?
        // $hashString = $data['idTransaction'] . $data['typeTransaction'] . $data['statusTransaction'] . $data['value'] . $data['payerName'] . $data['payerTaxId'] . $data['paymentDate'] . $data['paymentCode'] . $data['requestNumber'];
        // $expectedHash = hash('sha256', $hashString . $clientSecret);

        // if ($expectedHash !== $data['hash']) {
        //     // Se o hash não for válido, rejeitar a requisição
        //     Log::error('Hash inválido no webhook de PIX', ['data' => $data]);
        //     return response()->json(['error' => 'Invalid hash'], 400);
        // }

        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || $authorizationHeader != 'Webhook') {
            Log::error('Hash inválido no webhook de PIX', ['data' => $data]);
            return response()->json(['error' => 'Invalid header'], 400);
        }

        // Verificar se a transação existe com o `reference_code`
        $referenceCode = $data['message']['reference_code'];
        $transaction = Transaction::where('external_reference', $referenceCode)->first();

        if (!$transaction) {
            // Se a transação não for encontrada, registrar o erro
            Log::error('Transação não encontrada', ['reference_code' => $referenceCode]);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Atualizar o status da transação com base no status recebido
        if ($data['message']['status'] === 'paid') {
            $transaction->status = 'success';
            $responseMessage = 'Transaction updated successfully';
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

    public function createPayment($txnInfo)
    {
        $accessTokenResponse = $this->getAccessToken();

        // Verificar se a resposta contém o "access_token"
        if (isset($accessTokenResponse['access_token'])) {

            $qrCodeResponse = $this->generatePixQrCode($txnInfo, $accessTokenResponse);

            $qrCode = $qrCodeResponse['qrcode'];

            if (!isset($qrCode)) {
                return [
                    'status' => 'error',
                    'message' => 'Não foi possível efetuar o depósito agora, tente novamente mais tarde'
                ];
            }

            $paymentCode = $qrCode['reference_code'];
            $paymentCodeBase64 = $qrCode['image_base64'];
            $txnInfo['external_reference'] = $paymentCode;
            $txnInfo->save();

            return [
                'status' => 'success',
                'message' => [
                    'paymentCode' => $paymentCode,
                    'paymentCodeBase64' => $paymentCodeBase64
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Não foi possível efetuar o depósito agora, tente novamente mais tarde'
            ];
        }
    }

    private function getAccessToken()
    {
        $zendry = Gateway::where('gateway_code', 'zendry')->first();
        $credentials = json_decode($zendry->credentials, true);
        $clientId = $credentials['client_id'];
        $clientSecret = $credentials['client_secret'];
        $keyAuthorization = base64_encode($clientId . ':' . $clientSecret);

        // URL da API
        $url = $credentials['authorization_url'];

        // Cabeçalhos da requisição
        $headers = [
            'Authorization: Basic ' . $keyAuthorization,
            'Content-Type: application/x-www-form-urlencoded'
        ];

        // Inicializar cURL
        $curl = curl_init();

        // Configurar opções do cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
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

        return $decodedResponse;
    }

    private function generatePixQrCode($txnInfo, $decodedResponse)
    {

        $headers = [
            'Authorization: Bearer ' . $decodedResponse['access_token'],
            'Content-Type: application/json'
        ];

        $depositAmount = $this->convertDecimalToCentsString($txnInfo->pay_amount);

        $data = [
            "value_cents" => $depositAmount,
            "generator_name" => "John Doe",
            "generator_document" => "927.300.300-18",
            "expiration_time" => "1800",
            "external_reference" => $txnInfo->tnx
        ];

        // Inicializar cURL
        $curl = curl_init();

        // Configurar opções do cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.zendry.com.br/v1/pix/qrcodes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($data),
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
        return json_decode($response, true);
    }

    public function createWhitdraw($txnInfo, $totalAmount, $withdrawAccount)
    {
        $accessTokenResponse = $this->getAccessToken();

        // Verificar se a resposta contém o "access_token"
        if (isset($accessTokenResponse['access_token'])) {

            $whitdrawPix = $this->createWhitdrawPix($txnInfo, $totalAmount, $withdrawAccount, $accessTokenResponse);

            if (isset($whitdrawPix['payment']['reference_code'])) {
                $wPix = $whitdrawPix['payment']['reference_code'];
                $txnInfo['external_reference'] = $wPix;
                $txnInfo->save();

                return [
                    'status' => 'success',
                    'message' => 'Pagamento realizado com sucesso'
                ];
            } else {

                return [
                    'status' => 'error',
                    'message' => 'Não foi possível efetuar o saque agora, tente novamente mais tarde'
                ];
            }
        }
    }

    private function createWhitdrawPix($txnInfo, $totalAmount, $withdrawAccount, $decodedResponse)
    {
        $pixData = json_decode($withdrawAccount->credentials, true);
        $pixTypeKey = $pixData['Chave PIX']['type'];
        $pixKey = $pixData['Chave PIX']['value'];

        // Pesquisar a transação
        if ($pixData) {

            switch ($pixTypeKey) {
                case 'CPF':
                    $pixTypeKey = 'cpf';
                    break;
                case 'E-mail':
                    $pixTypeKey = 'email';
                    break;
                case 'Telefone':
                    $pixTypeKey = 'phone';
                    break;
                case 'aleatoria':
                    $pixTypeKey = 'token';
                    break;
                default:
                    break;
            }
        }

        $headers = [
            'Authorization: Bearer ' . $decodedResponse['access_token'],
            'Content-Type: application/json'
        ];

        $whitdrawAmount = $this->convertDecimalToCentsString($totalAmount);

        $data = [
            "initiation_type" => "dict",
            "idempotent_id" => $txnInfo->tnx,
            "value_cents" => $whitdrawAmount,
            "pix_key_type" => $pixTypeKey ?? null,
            "pix_key" => $pixKey ?? null,
            "authorized" => true
        ];

        // Inicializar cURL
        $curl = curl_init();

        // Configurar opções do cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.zendry.com.br/v1/pix/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($data),
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
        return json_decode($response, true);
    }

    private function convertDecimalToCentsString($decimal)
    {
        // Converte o valor para float e multiplica por 100 para obter os centavos
        $centavos = round($decimal * 100);

        // Remove quaisquer formatações que possam ser passadas, como R$, espaços e vírgulas
        $centavos = str_replace(['R$', ' ', '.'], '', $centavos);
        $centavos = str_replace(',', '.', $centavos); // Substitui a vírgula decimal por um ponto


        // Remove quaisquer casas decimais extras e retorna como string
        return (string) $centavos;
    }
}