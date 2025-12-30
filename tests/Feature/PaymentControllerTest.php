<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_payment_api()
    {
        // mock MidtransService
        $this->mock(\App\Services\MidtransService::class, function ($mock) {
            $mock->shouldReceive('createQris')
                ->once()
                ->andReturn(['url' => 'https://dummy-qris']);
        });

        $res = $this->postJson('/api/payments', [
            'external_ref' => 'KIOSK-TEST-003',
            'amount' => 70000,
            'items' => [
                ['id' => 'M1', 'name' => 'Latte', 'price' => 35000, 'qty' => 2]
            ]
        ]);

        $res->assertStatus(200)
            ->assertJsonStructure([
                'payment_id',
                'qris_url',
                'expires_at'
            ]);

        $this->assertDatabaseHas('payments', [
            'external_ref' => 'KIOSK-TEST-003',
            'status' => PaymentStatus::PENDING
        ]);
    }
}
