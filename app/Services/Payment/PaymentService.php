<?php

namespace App\Services\Payment;

use App\Jobs\SendInvoiceJob;
use App\Models\OrderTryout;
use App\Models\Payment;
use App\Services\Payment\MidtransService;

class PaymentService
{

    public function checkPendingTransaction($customerId)
    {
        // Get Transaction with Pending status
        $pendingTransactions = Payment::where('status_transaksi', 'pending')
            ->select('id', 'ref_order_id')
            ->where('customer_id', $customerId)
            ->get();

        foreach ($pendingTransactions as $transaction) {
            $checkStatus = $this->checkStatus($transaction->ref_order_id);
            if ($checkStatus['result'] === 'success') {
                $status = $checkStatus['data']['status'];
                if ($status !== 'pending') {
                    $transactionData = $checkStatus['data']['transaction'];
                    $this->updateStatus($transaction->id, $status, [
                        'transaction_id' => $transactionData->transaction_id,
                        'fraud_status' => $transactionData->fraud_status,
                        'payment_type' => $transactionData->payment_type,
                        'settlement_time' => @$transactionData->settlement_time,
                    ]);
                }
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

    public function updateStatus(string $id, string $status, array $transaction)
    {
        $payment = Payment::where('id', $id)->select('id', 'ref_order_id')->first();
        if (!$payment) {
            return [
                'result' => 'error',
                'title' => 'Could not found the transaction',
            ];
        }

        $payment->transaksi_id = $transaction['transaction_id'];
        $payment->status_fraud = $transaction['fraud_status'];
        $payment->metode = $transaction['payment_type'];
        $payment->status_transaksi = $status;
        if ($status === 'paid' && $transaction['settlement_time']) {
            $payment->waktu_transaksi = $transaction['settlement_time'];
        }
        $payment->save();

        $orderTryout = OrderTryout::find($payment->ref_order_id);
        if (!$orderTryout) {
            return [
                'result' => 'error',
                'title' => 'Could not found the Tryout',
            ];
        }

        $orderTryout->payment_id = $payment->id;
        $orderTryout->status_order = $status;
        $orderTryout->save();

        /* IDEA: Send Email transaction completed and other necessary action after transaction finished
        with checking the status first
        */

        if ($status === 'paid') {
            // Send emai invoice ke customer
            $orderInvoice = [
                'order_id' => $payment->ref_order_id
            ];
            SendInvoiceJob::dispatch($orderInvoice);
        }

        return [
            'result' => 'success',
            'title' => 'Transaction updated successfully',
        ];
    }
}
