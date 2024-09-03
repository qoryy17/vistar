<?php

namespace App\Helpers;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\OrderTryout;
use App\Models\PengaturanWeb;
use Illuminate\Support\Facades\DB;

class BerandaUI
{
    public static function customerTerdaftar()
    {
        return Customer::all()->count();
    }

    public static function customerTerdaftarPerhari()
    {
        return Customer::whereDate('created_at', today())->count();
    }

    public static function tryoutTerjual()
    {
        return OrderTryout::all()->where('status_order', 'paid')->count();
    }

    public static function tryoutTerjualPerhari()
    {
        return OrderTryout::whereDate('created_at', today())->where('status_order', 'paid')->count();
    }

    public static function statistikTryout($kategori = false)
    {
        return DB::table('order_tryout')->select('order_tryout.*', 'produk_tryout.kategori_produk_id', 'kategori_produk.judul', 'kategori_produk.status', 'kategori_produk.aktif')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', 'produk_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $kategori)
            ->where('order_tryout.status_order', 'paid')
            ->whereNot('kategori_produk.status', 'Gratis')
            ->whereNot('kategori_produk.aktif', 'T');
    }

    public static function sumTryoutPaid()
    {
        return Payment::where('status_transaksi', 'paid')->sum('nominal');
    }

    public static function sumTryoutPerhariPaid()
    {
        return Payment::where('status_transaksi', 'paid')->whereDate('created_at', today())->sum('nominal');
    }

    public static function sumTryoutPending()
    {
        return Payment::where('status_transaksi', 'pending')->sum('nominal');
    }

    public static function sumTryoutPerhariPending()
    {
        return Payment::where('status_transaksi', 'pending')->whereDate('created_at', today())->sum('nominal');
    }

    public static function web()
    {
        return PengaturanWeb::all()->first();
    }

    public static function countPenjualan($kategori, $tahun)
    {
        return DB::table('order_tryout')
            ->select(
                DB::raw('MONTH(order_tryout.created_at) as month'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $kategori)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->whereNot('kategori_produk.aktif', 'T')
            ->whereYear('order_tryout.created_at', $tahun)
            ->groupBy(DB::raw('MONTH(order_tryout.created_at)'))
            ->orderBy(DB::raw('MONTH(order_tryout.created_at)'))
            ->pluck('total_orders', 'month');
    }
}
