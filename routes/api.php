<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;


    Route::apiResource('users', UserController::class);
    Route::apiResource('products', ProductController::class);

    Route::get('gateways', [GatewayController::class, 'index']);
    Route::get('gateways/{gateway}', [GatewayController::class, 'show']);
    Route::patch('gateways/{gateway}/toggle', [GatewayController::class, 'toggle']);
    Route::patch('gateways/{gateway}/priority', [GatewayController::class, 'changePriority']);

    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/{client}', [ClientController::class, 'show']);

    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);

    Route::post('/transactions/{id}/refund', [TransactionController::class, 'refund']);
    Route::post('/purchase', [TransactionController::class, 'purchase']);


/*Route::middleware('auth:sanctum')->group(function () {


});*/