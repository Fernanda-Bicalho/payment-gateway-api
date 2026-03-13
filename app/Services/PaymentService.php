<?php

namespace App\Services;

use App\Models\Gateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function processPayment($transaction, $client)
    {
        $gateways = Gateway::where('is_active', true)
            ->orderBy('priority')
            ->get();

        foreach ($gateways as $gateway) {

            try {

                $response = Http::timeout(5)->post($gateway->url . '/pay', [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'client_email' => $client->email
                ]);

                if ($response->successful()) {

                    return [
                        'success' => true,
                        'gateway_id' => $gateway->id,
                        'external_id' => $response->json('external_id')
                    ];
                }

                Log::warning('Gateway retornou erro', [
                    'gateway' => $gateway->name,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

            } catch (\Exception $e) {

                Log::error('Erro no gateway', [
                    'gateway' => $gateway->name,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'success' => false
        ];
    }
}