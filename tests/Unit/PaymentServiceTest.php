<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PaymentService;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_payment_success()
    {
        $service = app(PaymentService::class);

        $payment = $service->create([
            'external_ref' => 'KIOSK-TEST-001',
            'amount' => 50000,
            'items' => [
                ['id' => 'M1', 'name' => 'Kopi', 'price' => 25000, 'qty' => 2]
            ]
        ]);

        $this->assertEquals(PaymentStatus::PENDING, $payment->status);
        $this->assertEquals(50000, $payment->amount);
        $this->assertNotNull($payment->expires_at);
    }

    public function test_prevent_double_payment()
    {
        $service = app(PaymentService::class);

        $first = $service->create([
            'external_ref' => 'KIOSK-TEST-002',
            'amount' => 30000,
            'items' => []
        ]);

        $second = $service->create([
            'external_ref' => 'KIOSK-TEST-002',
            'amount' => 30000,
            'items' => []
        ]);

        $this->assertEquals($first->id, $second->id);
    }
}
