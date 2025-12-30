<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentPageController extends Controller
{
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');

        return view('payments.success', [
            'orderId' => $orderId
        ]);
    }

    public function failed(Request $request)
    {
        return view('payments.failed');
    }
}
