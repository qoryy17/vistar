<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMitraTransaction;
use Auth;
use Illuminate\Http\Request;

class MitraTransactionController extends Controller
{
    public function index(Request $request)
    {
        $userTableName = User::getTableName();

        $userMitraTransactionTableName = UserMitraTransaction::getTableName();

        $transactions = UserMitraTransaction::where("$userMitraTransactionTableName.user_id_mitra", Auth::id())
            ->select(
                "$userMitraTransactionTableName.id",
                "$userMitraTransactionTableName.transaction_id",
                "$userMitraTransactionTableName.total_income",
                "$userMitraTransactionTableName.created_at",

                "user_buyer.name as buyer_name",
                "user_buyer.email as buyer_email",
            )
            ->leftJoin("$userTableName as user_buyer", "user_buyer.id", "=", "$userMitraTransactionTableName.user_id_buyer")
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('mitra.transaction.index', [
            'titlePage' => 'Transaksi Customer',
            'breadcrumbs' => [
                [
                    'title' => 'Dashboard',
                    'url' => route('mitra.dashboard'),
                    'active' => false,
                ],
                [
                    'title' => 'Transaksi',
                    'url' => route('mitra.transactions.index'),
                    'active' => true,
                ],
            ],
            'transactions' => $transactions,
        ]);
    }
}
