<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Payment\PaymentSertikomService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function notificationHandler(Request $request, $vendor)
    {
        $acceptedVendor = ['midtrans'];

        if (!in_array($vendor, $acceptedVendor)) {
            return response()->json([
                'result' => 'error',
                'title' => 'Unknown Payment Vendor',
            ], 403);
        }

        $transaction = null;

        if ($vendor === 'midtrans') {
            $midtransController = new MidtransController();
            $transaction = $midtransController->notificationHandler($request);
        }

        if (!$transaction) {
            return response()->json([
                'result' => 'error',
                'title' => 'Could not process the transaction',
            ], 500);
        }

        $paymentData = Payment::where('ref_order_id', $transaction['order_id'])->select('id')->first();
        if (!$paymentData) {
            return response()->json([
                'result' => 'error',
                'title' => 'Could not get the data',
            ], 500);
        }

        $paymentService = new PaymentService();
        $updateStatus = $paymentService->updateStatus($paymentData->id, $transaction['transaction_status'], $transaction);

        $paymentSertikomService = new PaymentSertikomService();
        $updateStatus = $paymentSertikomService->updateStatus($paymentData->id, $transaction['transaction_status'], $transaction);

        if ($updateStatus['result'] !== 'success') {
            return response()->json($updateStatus, 500);
        }

        return response()->json($updateStatus, 200);
    }

    public function callbackFinish(Request $request)
    {
        /* IDEA: Create Page for successfull payment and get the esential information from midtrans data and show to the user  */
        return response()->json(['result' => 'success', 'title' => 'Finish'], 200);
    }
    public function callbackUnFinish(Request $request)
    {
        /* IDEA: Create Page for not finished transaction and get the esential information from midtrans data and show to the user  */
        return response()->json(['result' => 'success', 'title' => 'Not Finish yet'], 200);
    }
    public function callbackError(Request $request)
    {
        /* IDEA: Create Page for error transaction and get the esential information from midtrans data and show to the user  */
        return response()->json(['result' => 'success', 'title' => 'Error'], 200);
    }
}
