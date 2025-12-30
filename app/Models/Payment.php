<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\PaymentStatus;
class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'external_ref',
        'amount',
        'method',
        'status',
        'snapshot',
        'expires_at',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'status'     => PaymentStatus::class,
        'snapshot'   => 'array',
        'payload'    => 'array',
        'expires_at' => 'datetime',
        'paid_at'    => 'datetime',
    ];
}
