<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'external_ref' => $this->faker->uuid,
            'amount' => 50000,
            'method' => 'QRIS',
            'status' => PaymentStatus::PENDING,
            'snapshot' => [],
        ];
    }
}
