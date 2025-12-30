<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentReportController;
use App\Http\Controllers\PaymentPageController;
Route::get('/', [PaymentReportController::class, 'index']);

Route::get('/payments/success', [PaymentPageController::class, 'success']);
Route::get('/payments/failed',  [PaymentPageController::class, 'failed']);
