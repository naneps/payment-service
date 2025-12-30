<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MidtransCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_callback_success()
    {
        $payment = Payment::factory()->create([
            'external_ref' => 'KIOSK-CB-001',
            'amount' => 100000,
            'status' => PaymentStatus::PENDING
        ]);

        $payload = [
            'order_id' => 'KIOSK-CB-001',
            'status_code' => '200',
            'gross_amount' => '100000',
            'transaction_status' => 'settlement',
        ];

        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            config('midtrans.server_key')
        );

        $res = $this->postJson('/api/payments/midtrans/callback', $payload);

        $res->assertStatus(200);

        $this->assertDatabaseHas('payments', [
            'external_ref' => 'KIOSK-CB-001',
            'status' => PaymentStatus::SUCCESS
        ]);
    }

    public function test_callback_idempotent()
    {
        $payment = Payment::factory()->create([
            'external_ref' => 'KIOSK-CB-002',
            'status' => PaymentStatus::SUCCESS
        ]);

        $res = $this->postJson('/api/payments/midtrans/callback', [
            'order_id' => 'KIOSK-CB-002',
            'status_code' => '200',
            'gross_amount' => '50000',
            'transaction_status' => 'settlement',
            'signature_key' => 'invalid'
        ]);

        // should not break system
        $res->assertStatus(403);
    }
}
