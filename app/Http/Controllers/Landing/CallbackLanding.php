<?php

namespace App\Http\Controllers\Landing;

use Midtrans\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CallbackLanding extends Controller
{
    public function callbackPaymentSuccess(Request $request)
    {
        // Setup konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Ambil notifikasi dari Midtrans
        $notification = $request->all();
        $orderId = $notification['order_id'];
        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'];

        // Temukan order berdasarkan order_id
        // $order = Order::where('id', $orderId)->first();

        // if ($order) {
        //     if ($transactionStatus == 'capture') {
        //         if ($fraudStatus == 'accept') {
        //             // Pembayaran berhasil
        //             $order->status = 'paid';
        //         } else if ($fraudStatus == 'deny') {
        //             // Pembayaran ditolak
        //             $order->status = 'failed';
        //         }
        //     } else if ($transactionStatus == 'settlement') {
        //         // Pembayaran berhasil dan settled
        //         $order->status = 'paid';
        //     } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
        //         // Pembayaran dibatalkan, ditolak, atau expired
        //         $order->status = 'failed';
        //     } else if ($transactionStatus == 'pending') {
        //         // Pembayaran masih menunggu
        //         $order->status = 'pending';
        //     }

        //     // Simpan perubahan status order
        //     $order->save();

        //     $data  = [
        //         'title' => 'Pembayaran Berhasil',
        //     ];
        //     return view('main-web.callback.callback-payment-success', $data);
        // } else {
        //     return response()->json(['message' => 'Order not found'], 404);
        // }
    }
}
