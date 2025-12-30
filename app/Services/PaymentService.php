<?php

namespace App\Services;

use App\Models\Payment;
use App\Enums\PaymentStatus;

class PaymentService
{
    public function create(array $data): Payment
    {
        $existing = Payment::where('external_ref', $data['external_ref'])
            ->whereIn('status', [PaymentStatus::PENDING])
            ->first();

        if ($existing) {
            return $existing;
        }

        return Payment::create([
            'external_ref' => $data['external_ref'],
            'amount'       => $data['amount'],
            'method'       => strtoupper($data['channel']), // QRIS / SNAP
            'status'       => PaymentStatus::PENDING,
            'snapshot'     => [
                'items' => $data['items'],
                'total' => $data['amount'],
            ],
            'expires_at'   => now()->addMinutes(5),
        ]);
    }

    public function markSuccess(Payment $payment, array $payload = []): void
    {
        $payment->update([
            'status'  => PaymentStatus::SUCCESS,
            'paid_at' => now(),
            'payload' => $payload,
        ]);
    }
}
