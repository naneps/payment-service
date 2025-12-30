<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Enums\PaymentStatus;

class PaymentSimulationController extends Controller
{
    public function success(
        Payment $payment,
        PaymentService $paymentService
    ) {
        // 🔒 Safety: hanya boleh di non-production
        if (app()->environment('production')) {
            abort(403, 'Simulation disabled in production');
        }

        if ($payment->status !== PaymentStatus::PENDING) {
            return response()->json([
                'message' => 'Payment already processed',
                'status'  => $payment->status,
            ], 400);
        }

        // payload palsu tapi STRUKTURNYA MIDTRANS
        $fakePayload = [
            'order_id'            => $payment->external_ref,
            'transaction_status'  => 'settlement',
            'payment_type'        => 'qris',
            'gross_amount'        => (string) $payment->amount,
            'fraud_status'        => 'accept',
            'transaction_time'    => now()->toIso8601String(),
            'signature_key'       => 'SIMULATED',
        ];

        $paymentService->markSuccess($payment, $fakePayload);

        return response()->json([
            'message' => 'Payment simulated as SUCCESS',
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'paid_at' => $payment->paid_at,
        ]);
    }
}
