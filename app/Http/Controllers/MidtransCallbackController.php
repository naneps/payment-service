<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\PaymentService;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request, PaymentService $paymentService)
    {
        $payload = $request->all();

        $payment = Payment::where('external_ref', $payload['order_id'])->first();

        if (! $payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if (in_array($payload['transaction_status'], ['settlement', 'capture'])) {
            $paymentService->markSuccess($payment, $payload);
        }

        return response()->json(['message' => 'OK']);
    }
}
