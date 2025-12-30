<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentSimulationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransCallbackController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/payments', [PaymentController::class, 'index']);

Route::post('/payments', [PaymentController::class, 'store']);
Route::get('/payments/{payment}', [PaymentController::class, 'show']);
Route::post('/payments/midtrans/callback', [MidtransCallbackController::class, 'handle']);
Route::post('/payments/{payment}/simulate-success', [
    PaymentSimulationController::class,
    'success'
]);



