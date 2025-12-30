<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\MidtransService;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Services\PaymentReportService;
use App\Http\Resources\PaymentResource;

class PaymentController extends Controller
{

    public function index(
        Request $request,
        PaymentReportService $reportService
    ) {
        $payments = $reportService->paginate(
            $request->only([
                'status',
                'channel',
                'q',
                'from',
                'to',
                'per_page',
            ])
        );

        return PaymentResource::collection($payments)
            ->additional([
                'meta' => [
                    'page'      => $payments->currentPage(),
                    'per_page'  => $payments->perPage(),
                    'total'     => $payments->total(),
                    'last_page' => $payments->lastPage(),
                ],
            ]);
    }

    public function store(
        Request $r,
        PaymentService $paymentService,
        MidtransService $midtrans
    ) {
        $data = $r->validate([
            'external_ref' => 'required|string',
            'amount'       => 'required|integer|min:1',
            'items'        => 'required|array|min:1',
            'channel'      => 'required|in:qris,snap',
        ]);

        $payment = $paymentService->create($data);

        if ($payment->status !== PaymentStatus::PENDING) {
            return response()->json([
                'payment_id' => $payment->id,
                'status'     => $payment->status,
                'paid_at'    => $payment->paid_at,
            ]);
        }

        // ===== QRIS =====
        if ($data['channel'] === 'qris') {
            $qris = $midtrans->createQris(
                $payment->external_ref,
                $payment->amount
            );

            if (empty($qris['qr_image_url'])) {
                abort(500, 'QRIS image URL not found');
            }

            return response()->json([
                'payment_id'    => $payment->id,
                'type'          => 'QRIS',
                'qris_url'      => $qris['qr_image_url'],
                'expires_at'    => $payment->expires_at,
                'simulator_url' => $midtrans->buildQrisSimulatorUrl(
                    $payment->external_ref
                ),
            ]);
        }

        // ===== SNAP =====
        if ($data['channel'] === 'snap') {
            $snap = $midtrans->createSnap(
                $payment->external_ref,
                $payment->amount,
                $data['items']
            );

            return response()->json([
                'payment_id' => $payment->id,
                'type'       => 'SNAP',
                'snap_token' => $snap['snap_token'],
                'snap_url'   => $snap['snap_url'],
                'expires_at' => $payment->expires_at,
            ]);
        }
    }

    public function show(Payment $payment)
    {
        return response()->json([
            'payment_id' => $payment->id,
            'status'     => $payment->status,
            'paid_at'    => $payment->paid_at,
            'expires_at' => $payment->expires_at,
        ]);
    }
}
