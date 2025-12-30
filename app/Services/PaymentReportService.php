<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentReportService
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        $q = Payment::query();

        // filter status
        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        // filter channel
        if (!empty($filters['channel'])) {
            $q->where('method', strtoupper($filters['channel']));
        }

        // search external ref
        if (!empty($filters['q'])) {
            $q->where('external_ref', 'like', '%' . $filters['q'] . '%');
        }

        // filter date range
        if (!empty($filters['from'])) {
            $q->whereDate('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $q->whereDate('created_at', '<=', $filters['to']);
        }

        return $q
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(
                perPage: (int) ($filters['per_page'] ?? 15)
            );
    }
}
