<?php

namespace App\Services;

use Midtrans\CoreApi;
use Midtrans\Snap;
use Midtrans\Config;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
    }

    /* =========================
       QRIS (CORE API)
    ========================= */
    public function createQris(string $orderId, int $amount): array
    {
        $response = CoreApi::charge([
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
        ]);

        $actions = collect($response->actions ?? []);

        return [
            'type'         => 'QRIS',
            'order_id'     => $response->order_id ?? null,
            'status'       => $response->transaction_status ?? null,
            'expiry_time'  => $response->expiry_time ?? null,
            'qr_image_url' => $actions->firstWhere('name', 'generate-qr-code')?->url,
            'raw'          => $response,
        ];
    }

    /* =========================
       SNAP (REDIRECT / POPUP)
    ========================= */
    public function createSnap(string $orderId, int $amount, array $items): array
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => collect($items)->map(fn ($item) => [
                'id'       => $item['id'] ?? null,
                'price'    => $item['price'],
                'quantity' => $item['qty'] ?? 1,
                'name'     => $item['name'],
            ])->toArray(),
        ];

        $snap = Snap::createTransaction($params);

        return [
            'type'       => 'SNAP',
            'snap_token' => $snap->token,
            'snap_url'   => $snap->redirect_url,
        ];
    }

    /* =========================
       QRIS SIMULATOR (SANDBOX)
    ========================= */
    public function buildQrisSimulatorUrl(string $orderId): string
    {
        if (config('midtrans.is_production')) {
            return '';
        }

        return 'https://simulator.sandbox.midtrans.com/v2/qris/payment'
            . '?order_id=' . urlencode($orderId);
    }
}
