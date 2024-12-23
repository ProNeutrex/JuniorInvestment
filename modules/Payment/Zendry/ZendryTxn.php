<?php

namespace Payment\Zendry;

use App\Models\Gateway;

class ZendryTxn {

    public function __construct()
    {
    }

    public function createPayment($info, $gatewayInfo)
    {
        $user = auth()->user();

        $accessTokenResponse = $this->getAccessToken($gatewayInfo);

        // Verificar se a resposta contém o "access_token"
        if (isset($accessTokenResponse['access_token'])) {

            $qrCodeResponse = $this->generatePixQrCode($info, $accessTokenResponse);

            $qrCode = $qrCodeResponse['qrcode'];

            if (!isset($qrCode)) {
                notify()->error('Não foi possível efetuar o depósito agora, tente novamente mais tarde', 'Error');
                return redirect()->back()->with('error', 'Não foi possível efetuar o depósito agora, tente novamente mais tarde');
            }

            $paymentCode = $qrCode['reference_code'];
            $paymentCodeBase64 = $qrCode['image_base64'];
            // Exibir o paymentCode e o QR code em base64
            return view('frontend.money_invest.deposit.suitpay', compact('paymentCode', 'paymentCodeBase64'));
        } else {
            notify()->error('Não foi possível efetuar o depósito agora, tente novamente mais tarde', 'Error');
            return redirect()->back()->with('error', 'Não foi possível efetuar o depósito agora, tente novamente mais tarde');
        }
    }

    private function getAccessToken($gatewayInfo)
    {
        $suitpay = Gateway::find($gatewayInfo->gateway_id);
        $credentials = json_decode($suitpay->credentials, true);
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

    private function generatePixQrCode($info, $decodedResponse)
    {

        $headers = [
            'Authorization: Bearer ' . $decodedResponse['access_token'],
            'Content-Type: application/json'
        ];

        $data = [
            "value_cents" => "100",
            "generator_name" => "John Doe",
            "generator_document" => "927.300.300-18",
            "expiration_time" => "1800",
            "external_reference" => "Teste2"
        ];

        // Inicializar cURL
        $curl = curl_init();

        // Configurar opções do cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.zendry.com.br/v1/pix/qrcodes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => '{
    "value_cents": "100",
    "generator_name": "John Doe",
    "generator_document": "927.300.300-18",
    "expiration_time": "1800",
    "external_reference": "Teste2"
}',
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
}