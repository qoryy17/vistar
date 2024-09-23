<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\UserMitra;
use App\Models\UserMitraTransaction;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardMitraController extends Controller
{
    public function index(Request $request)
    {
        $userMitra = UserMitra::where('user_id', Auth::id())->first();
        if (!$userMitra) {
            return redirect()->back()->with('error', 'Data anda tidak ditemukan.');
        }

        $transactionAll = UserMitraTransaction::select(DB::raw('SUM(total_income) as total_income'), DB::raw('count(total_income) as total_transaction'))
            ->where('user_id_mitra', Auth::id())
            ->first();
        $transactionToday = UserMitraTransaction::select(DB::raw('SUM(total_income) as total_income'), DB::raw('count(total_income) as total_transaction'))
            ->where('user_id_mitra', Auth::id())
            ->where('created_at', '>=', Carbon::today()->toDateString())
            ->first();

        $totalIncome = $transactionAll?->total_income ?? 0;
        $totalTransaction = $transactionAll?->total_transaction ?? 0;

        $totalIncomeToday = $transactionToday?->total_income ?? 0;
        $totalTransactionToday = $transactionToday?->total_transaction ?? 0;

        return view('mitra.dashboard', [
            'titlePage' => 'Dashboard Mitra',
            'breadcrumbs' => [
                [
                    'title' => 'Dashboard',
                    'url' => route('mitra.dashboard'),
                    'active' => true,
                ],
            ],
            'userMitra' => $userMitra,
            'statistics' => [
                'total_income' => $totalIncome,
                'total_income_today' => $totalIncomeToday,
                'total_transaction' => $totalTransaction,
                'total_transaction_today' => $totalTransactionToday,
            ],
        ]);
    }
}
