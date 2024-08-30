<?php

namespace App\Http\Controllers\Landing;

use Midtrans\Config;
use App\Models\Payment;
use App\Models\OrderTryout;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Midtrans extends Controller
{
    public function callbackTransaction()
    {
        return view('main-web.callback.callback-transaction');
    }

    public function notificationHandler(Request $request)
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
        $payID = Str::uuid();
        $order = OrderTryout::where('id', $orderId)->first();
        $order->payment_id = $payID;

        // Konversi ke integer
        $amount = (int) $notification['gross_amount'];

        $payment = new Payment();
        $payment->id = $payID;
        $payment->ref_order_id = $orderId;
        $payment->transaksi_id = $notification['transaction_id'];
        $payment->nominal = $amount;
        $payment->metode = $notification['payment_type'];
        if ($order) {
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Pembayaran berhasil
                    $order->status_order = 'paid';
                    $title = 'Pembayaran Berhasil';
                    $message = 'Terimakasih telah melakukan pembayaran, kini anda dapat menikmati ujian Tryout Berbayar. Silahkan klik tombol dibawah ini untuk melanjutkan';
                    $payment->status_transaksi = $transactionStatus;
                    $payment->status_fraud = $fraudStatus;
                    $redirectWeb = 'site.tryout-berbayar';
                } else if ($fraudStatus == 'deny') {
                    // Pembayaran ditolak
                    $order->status_order = 'failed';
                    $title = 'Pembayaran Ditolak';
                    $message = 'Pembayaran anda ditolak, silahkan hubungi kami lebih lanjut';
                    $payment->status_transaksi = $transactionStatus;
                    $payment->status_fraud = $fraudStatus;
                    $redirectWeb = 'mainweb.index';
                }
            } else if ($transactionStatus == 'settlement') {
                // Pembayaran berhasil dan settled
                $order->status_order = 'paid';
                $title = 'Pembayaran Berhasil';
                $message = 'Terimakasih telah melakukan pembayaran, kini anda dapat menikmati ujian Tryout Berbayar. Silahkan klik tombol dibawah ini untuk melanjutkan';
                $payment->status_transaksi = $transactionStatus;
                $payment->status_fraud = $fraudStatus;
                $redirectWeb = 'mainweb.index';
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                // Pembayaran dibatalkan, ditolak, atau expired
                $order->status_order = 'failed';
                $title = 'Pembayaran Gagal';
                $message = 'Pembayaran gagal, silahkan hubungi kami lebih lanjut';
                $payment->status_transaksi = $transactionStatus;
                $payment->status_fraud = $fraudStatus;
                $redirectWeb = 'mainweb.index';
            } else if ($transactionStatus == 'pending') {
                // Pembayaran masih menunggu
                $order->status_order = 'pending';
                $title = 'Pembayaran Pending';
                $message = 'Pembayaran pending, silahkan hubungi kami lebih lanjut';
                $payment->status_transaksi = $transactionStatus;
                $payment->status_fraud = $fraudStatus;
                $redirectWeb = 'mainweb.index';
            }

            // Simpan perubahan status order dan pembayaran
            $order->save();
            $payment->waktu_transaksi = $notification['transaction_time'];
            $payment->metadata = json_encode($notification);
            $payment->save();

            $data  = [
                'title' => $title,
                'message' => $message,
                'redirect' => $redirectWeb
            ];
            return view('main-web.callback.callback-response', $data);
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }
}
