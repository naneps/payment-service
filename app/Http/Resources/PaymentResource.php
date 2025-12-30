<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'payment_id'   => $this->id,
            'external_ref' => $this->external_ref,
            'channel'      => $this->method,
            'status'       => $this->status->value,
            'amount'       => $this->amount,
            'paid_at'      => $this->paid_at,
            'expires_at'   => $this->expires_at,
            'created_at'   => $this->created_at,
        ];
    }
}
