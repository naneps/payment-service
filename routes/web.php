<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentReportController;

Route::get('/', [PaymentReportController::class, 'index']);
