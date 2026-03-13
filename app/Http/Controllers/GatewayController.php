<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{

    public function index()
    {
        $gateways = Gateway::orderBy('priority')->get();

        return response()->json([
            'data' => $gateways
        ], 200);
    }


    public function show($id)
    {
        $gateway = Gateway::findOrFail($id);

        return response()->json([
            'data' => $gateway
        ]);
    }

    public function toggle($id)
    {
        $gateway = Gateway::findOrFail($id);

        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        return response()->json([
            'message' => 'Gateway atualizado',
            'data' => $gateway
        ], 200);
    }


    public function changePriority(Request $request, $gateway)
{
    $gateway = Gateway::findOrFail($gateway);

    $request->validate([
        'priority' => 'required|integer|min:1'
    ]);

    $gateway->update([
        'priority' => $request->priority
    ]);

    return response()->json([
        'message' => 'Prioridade atualizada com sucesso',
        'gateway' => $gateway
    ]);
}
}
