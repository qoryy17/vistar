<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Notifikasi;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class Referral extends Controller
{
    public function index()
    {
        $referralCustomer = DB::table('users')->select(
            'users.kode_referral',
            'users.created_at',
            'users.updated_at',
            'customer.nama_lengkap'
        )->rightJoin('customer', 'users.customer_id', '=', 'customer.id')->get();

        $data = [
            'form_title' => 'Data Referral Customer',
            'page_title' => 'Referral Customer',
            'bc1' => 'Dashboard',
            'bc2' => 'Manajemen Referral',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'referral' => $referralCustomer
        ];

        return view('main-panel.referral.data-referral', $data);
    }

    public function detailReferral(Request $request)
    {
        $referralBank = DB::table('bank_referral')->select(
            'bank_referral.*',
            'customer.nama_lengkap',
            'produk_tryout.nama_tryout'
        )->leftJoin('customer', 'bank_referral.customer_id', '=', 'customer.id')
            ->leftJoin('produk_tryout', 'bank_referral.produk_tryout_id', 'produk_tryout.id')->where('kode_referral', $request->kodeReferral)->get();

        $data = [
            'form_title' => 'Detil Referral Customer',
            'page_title' => 'Referral ' . $request->namaLengkap,
            'bc1' => 'Referral',
            'bc2' => 'Referral ' . $request->namaLengkap,
            'customer' => $request->namaLengkap,
            'kodeReferral' => $request->kodeReferral,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'referral' => $referralBank
        ];

        return view('main-panel.referral.detil-referral', $data);
    }
}
