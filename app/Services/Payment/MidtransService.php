<?php

namespace App\Services\Payment;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{

    public function __construct()
    {
        // Setup konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Create Order
     *
     * @param  array $params Payment options [ orderID: any, grossAmount: number, customerFullName: string, customerEmail: string, customerPhone: string, customerBillingAddress: string, itemDetails: array([ id: any, price: number, quantity: number, name: string ]) ]
     * @return string Snap token
     * @throws Exception curl error or midtrans error
     */
    public function createOrder($orderDetails): string
    {
        $payload = [
            'transaction_details' => [
                'order_id' => $orderDetails['orderID'],
                'gross_amount' => intval($orderDetails['grossAmount']),
            ],
            'customer_details' => [
                'first_name' => $orderDetails['customerFullName'],
                'email' => $orderDetails['customerEmail'],
                'phone' => $orderDetails['customerPhone'],
                'billing_address' => $orderDetails['customerBillingAddress'],
            ],
            'item_details' => $orderDetails['itemDetails'],
            'callbacks' => [
                'finish' => url('payment/finish'),
                'notification' => url('payment/notification/handler'),
            ],
        ];

        return Snap::getSnapToken($payload);
    }

    /**
     * Check Order Status by OrderId
     *
     * @param string $params orderId
     * @return array
     * @throws array
     */
    public function checkStatus(string $orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);

            return [
                'result' => 'success',
                'title' => 'Successfully get transaction status',
                'data' => $status,
            ];
        } catch (\Exception $e) {
            return [
                'result' => 'error',
                'title' => $e->getMessage(),
            ];
        }
    }

    /**
     * Change Transaction Status
     *
     * @param string $params transactionStatus
     *
     * @return string status
     */
    public static function changeStatusToAppStandarization(string $transactionStatus): string
    {
        $status = $transactionStatus;
        if ($transactionStatus === 'settlement') {
            $status = 'paid';
        } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny') {
            $status = 'failed';
        } elseif ($transactionStatus === 'pending') {
            $status = 'pending';
        } elseif ($transactionStatus === 'expired' || $transactionStatus === 'expire') {
            $status = 'expired';
        }

        return $status;
    }

}
