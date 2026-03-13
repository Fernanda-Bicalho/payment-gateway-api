<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ClientController extends Controller
{

    public function index()
    {
        $clients = Client::all();

        return response()->json([
            'data' => $clients
        ], 200);
    }


    public function show(Client $client)
    {
        $client->load('transactions');

        return response()->json([
            'data' => $client
        ], 200);
    }
}