<?php

namespace App\Services\Payment;

use App\Jobs\SendInvoiceJob;
use App\Models\OrderTryout;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserMitra;
use App\Models\UserMitraTransaction;
use App\Services\Payment\MidtransService;

class PaymentService
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

        $userCustomer = User::select('id', 'name', 'email')
            ->where('customer_id', $payment->customer_id)
            ->whereNotNull('customer_id')
            ->first();

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

        if ($status === 'paid') {
            // Add transaction mitra if there is promotion code
            if ($payment->promo_type && $payment->promo_code) {
                if ($payment->promo_type === 'mitra') {
                    $userMitra = UserMitra::select('id', 'user_id', 'balances', 'user_benefit_type', 'user_benefit_value')
                        ->where('promotion_code', $payment->promo_code)
                        ->first();
                    if ($userMitra) {
                        $totalTransaction = $payment->subtotal;
                        $totalIncome = 0;
                        if ($userMitra->user_benefit_type === 'percent') {
                            $totalIncome = $totalTransaction * $userMitra->user_benefit_value / 100;
                        } elseif ($userMitra->user_benefit_type === 'deduction') {
                            $totalIncome = $userMitra->user_benefit_value;
                        }

                        // Add Transaction
                        UserMitraTransaction::create([
                            'user_id_mitra' => $userMitra->user_id,
                            'user_id_buyer' => $userCustomer?->id,
                            'transaction_id' => $payment->id,
                            'total_transaction' => $totalTransaction,
                            'total_income' => $totalIncome,
                            'promotion_data' => $payment->promo_data,
                        ]);

                        // Update Balance Mitra
                        $userMitra->update([
                            'balances' => $userMitra->balances + $totalIncome,
                        ]);
                    }

                } elseif ($payment->promo_type === 'referral') {
                    /* NOTE: Skip for now */
                }
            }

            // Send emai invoice ke customer
            $orderInvoice = [
                'order_id' => $payment->ref_order_id,
            ];
            SendInvoiceJob::dispatch($orderInvoice);
        }

        return [
            'result' => 'success',
            'title' => 'Transaction updated successfully',
        ];
    }
}
