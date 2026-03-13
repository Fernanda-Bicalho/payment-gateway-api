
<?php

    namespace App\Services\Gateways;

    use Illuminate\Support\Facades\Http;

    class GatewayTwo implements GatewayInterface
    {
        public function charge(array $data)
        {
            $response = Http::post('http://gateway-mock:3002/transactions', $data);

            return $response->json();
        }

        public function refund(string $transactionId)
        {
            $response = Http::post("http://gateway-mock:3002/transactions/{$transactionId}/refund");

            return $response->json();
        }
    }