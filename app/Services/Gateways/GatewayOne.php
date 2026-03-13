<?php

namespace App\Services\Gateways;

use Illuminate\Support\Facades\Http;

class GatewayOne implements GatewayInterface
{
    public function charge(array $data)
    {
        $response = Http::post('http://host.docker.internal:3001/pay', $data);

        return $response->json();
    }

    public function refund(string $transactionId)
    {
        return Http::post("http://host.docker.internal:3001/refund/$transactionId");
    }
}

