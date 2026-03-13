<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::with(['client','gateway','products'])->paginate(10);

        return response()->json($transactions, 200);
    }


    public function show($id)
{
    $transaction = Transaction::with(['client','products','gateway'])
        ->findOrFail($id);

    return response()->json($transaction);
}

    public function refund($id) {
    $transaction = Transaction::with('gateway')->findOrFail($id);

    if ($transaction->status !== 'paid') {
        return response()->json([
            'message' => 'Apenas transações pagas podem ser reembolsadas'
        ],400);
    }

    $response = Http::post($transaction->gateway->url."/refund", [
        'transaction_id' => $transaction->external_id
    ]);

    if ($response->successful()) {

        $transaction->status = 'refunded';
        $transaction->save();

        return response()->json([
            'message' => 'Reembolso realizado',
            'data' => $transaction
        ],200);
    }

    return response()->json([
        'message' => 'Falha ao realizar reembolso'

    ],500);

    }
    public function purchase(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'products' => 'required|array|min:1',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    $client = \App\Models\Client::findOrFail($request->client_id);

    $transaction = Transaction::create([
        'client_id' => $client->id,
        'status' => 'pending',
        'amount' => 0,
        'card_last_numbers' => '',
    ]);

    $totalAmount = 0;

    foreach ($request->products as $p) {
        $product = \App\Models\Product::find($p['id']);

        $transaction->products()->attach($product->id, [
            'quantity' => $p['quantity']
        ]);

        $totalAmount += $product->amount * $p['quantity'];
    }

    $transaction->amount = $totalAmount;
    $transaction->save();

    $gateways = \App\Models\Gateway::where('is_active', true)
        ->orderBy('priority')
        ->get();

    $paid = false;

    foreach ($gateways as $gateway) {

        try {

            $response = Http::post("http://localhost:" . ($gateway->priority + 3000) . "/pay", [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'client_email' => $client->email
            ]);

            if ($response->successful()) {

                $transaction->gateway_id = $gateway->id;
                $transaction->status = 'paid';
                $transaction->external_id = $response->json()['external_id'] ?? null;
                $transaction->save();

                $paid = true;

                break;
            }

        } catch (\Exception $e) {

            Log::error('Erro no gateway', [
                'gateway' => $gateway->name,
                'error' => $e->getMessage()
            ]);

            continue;
        }
    }

    if (!$paid) {

        $transaction->status = 'failed';
        $transaction->save();

        return response()->json([
            'message' => 'Falha ao processar a compra',
            'data' => $transaction
        ], 500);
    }

    return response()->json([
        'message' => 'Compra realizada com sucesso',
        'data' => $transaction
    ], 200);
}

}