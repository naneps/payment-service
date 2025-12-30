<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentReportService;

class PaymentReportController extends Controller
{
    public function index(
        Request $request,
        PaymentReportService $service
    ) {
        $filters = $request->only(['q', 'status', 'channel']);

        $payments = $service->paginate($filters);

        return view('payments.index', [
            'payments' => $payments,
            'filters'  => $filters,
        ]);
    }
}
