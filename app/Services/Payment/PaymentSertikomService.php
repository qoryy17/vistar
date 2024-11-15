<?php

namespace App\Services\Payment;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Services\Payment\MidtransService;
use App\Jobs\Sertikom\SendSertikomInvoiceJob;
use App\Models\Sertikom\OrderPelatihanSeminarModel;

class PaymentSertikomService
{

    public function checkPendingTransaction($customerId)
    {
        // Get Transaction with Pending status
        $pendingTransactions = Payment::where('status_transaksi', 'pending')
            ->select('id', 'ref_order_id', 'transaksi_id')
            ->where('customer_id', $customerId)
            ->get();

        foreach ($pendingTransactions as $transaction) {
            $checkStatus = $this->checkStatus($transaction->ref_order_id);
            if ($checkStatus['result'] === 'success') {
                $status = $checkStatus['data']['status'];
                if (is_null($transaction->transaksi_id) || $status !== 'pending') {
                    $transactionData = $checkStatus['data']['transaction'];
                    $this->updateStatus($transaction->id, $status, [
                        'transaction_id' => $transactionData->transaction_id,
                        'fraud_status' => $transactionData->fraud_status,
                        'payment_type' => $transactionData->payment_type,
                        'settlement_time' => @$transactionData->settlement_time,
                    ]);
                }
            } elseif (array_key_exists('data', $checkStatus) && array_key_exists('status', $checkStatus['data'])) {
                $this->updateStatus($transaction->id, $checkStatus['data']['status']);
            }
        }
    }

    public function checkStatus(string $orderId)
    {
        $midtransService = new MidtransService();

        $checkStatus = $midtransService->checkStatus($orderId);
        if (!$checkStatus) {
            return [
                'result' => 'error',
                'title' => 'Could not found the transaction',
            ];
        }

        if ($checkStatus['result'] !== "success") {
            return $checkStatus;
        }

        $transaction = $checkStatus['data'];

        $transactionStatus = $transaction->transaction_status;

        return [
            'result' => 'success',
            'title' => 'Successfully get transaction status',
            'data' => [
                'status' => MidtransService::changeStatusToAppStandarization($transactionStatus),
                'transaction' => $transaction,
            ],
        ];
    }

    public function updateStatus(string $id, string $status, array | null $transaction = null)
    {
        $payment = Payment::where('id', $id)
            ->select('id', 'ref_order_id', 'customer_id', 'subtotal', 'promo_type', 'promo_code', 'promo_data', 'nominal')
            ->first();
        if (!$payment) {
            return [
                'result' => 'error',
                'title' => 'Could not found the transaction',
            ];
        }

        if ($transaction) {
            $payment->transaksi_id = $transaction['transaction_id'];
            $payment->status_fraud = $transaction['fraud_status'];
            $payment->metode = $transaction['payment_type'];
            if ($status === 'paid' && $transaction['settlement_time']) {
                $payment->waktu_transaksi = $transaction['settlement_time'];
            }
        }
        $payment->status_transaksi = $status;
        $payment->save();

        $orderSertikom = OrderPelatihanSeminarModel::find($payment->ref_order_id);
        if (!$orderSertikom) {
            return [
                'result' => 'error',
                'title' => 'Could not found product',
            ];
        }

        // Search category sertikom : training or seminar/workshop
        $searchCategory = DB::table('produk_pelatihan_seminar')->select(
            'produk_pelatihan_seminar.*',
            'kategori_produk.judul'
        )->leftJoin('kategori_produk', 'kategori_produk.id', '=', 'produk_pelatihan_seminar.kategori_produk_id')
            ->where('produk_pelatihan_seminar.id', $orderSertikom->produk_pelatihan_seminar_id)
            ->first();

        if (!$searchCategory) {
            return [
                'result' => 'error',
                'title' => 'Could not found category',
            ];
        }

        $orderSertikom->payment_id = $payment->id;
        $orderSertikom->status_order = $status;
        $orderSertikom->save();

        if ($status === 'paid') {

            // Send email invoice ke customer
            $orderInvoice = [
                'order_id' => $payment->ref_order_id,
                'category' => $searchCategory->judul,
            ];
            SendSertikomInvoiceJob::dispatch($orderInvoice);
        }

        return [
            'result' => 'success',
            'title' => 'Transaction updated successfully',
        ];
    }
}
