<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\MidtransService;
use Exception;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function notificationHandler()
    {
        // Setup konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            // To get notification must using Midtrans Notification otherwise will leak using fake payment
            $notification = new \Midtrans\Notification();

            $paymentType = @$notification->payment_type;
            $transactionId = @$notification->transaction_id;
            $currency = @$notification->currency;

            $store = @$notification->store;
            // Company Code
            $billerCode = @$notification->biller_code;

            $bankName = null;

            $paymentCode = @$notification->payment_code;
            // get VA if using Bank Transfer
            $va_numbers = @$notification->va_numbers;
            $permata_va_number = @$notification->permata_va_number;
            // Get bill key if using
            $bill_key = @$notification->bill_key;

            if (strtolower($paymentType) == "bank_transfer") {
                if ($permata_va_number != "" && $permata_va_number != null) {
                    $bankName = 'permata';
                    $paymentCode = $permata_va_number;
                } elseif (is_array($va_numbers)) {
                    $bankName = @$va_numbers[0]->bank;
                    $paymentCode = @$va_numbers[0]->va_number;
                }
            } elseif (strtolower($paymentType) == "echannel") {
                $bankName = 'mandiri';
                $paymentCode = $bill_key;
            }

            $transactionStatus = @$notification->transaction_status;
            $status = MidtransService::changeStatusToAppStandarization($transactionStatus);

            $orderId = @$notification->order_id;
            $fraudStatus = @$notification->fraud_status;
            $settlementTime = @$notification->settlement_time;

            return [
                'order_id' => $orderId,
                'transaction_status' => $status,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
                'transaction_id' => $transactionId,
                'currency' => $currency,
                'store' => $store,
                'biller_code' => $billerCode,
                'bankName' => $bankName,
                'payment_code' => $paymentCode,
                'settlement_time' => $settlementTime,
            ];

        } catch (\Throwable $th) {
            throw new Exception("Failed get Notification Data. " . $th->getMessage());
        }
    }
}
