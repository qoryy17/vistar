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
        return OrderTryout::all()->count();
    }

    public static function tryoutTerjualPerhari()
    {
        return OrderTryout::whereDate('created_at', today())->count();
    }

    public static function statistikTryout($kategori = false)
    {
        return DB::table('order_tryout')->select('order_tryout.*', 'produk_tryout.kategori_produk_id', 'kategori_produk.judul', 'kategori_produk.status', 'kategori_produk.aktif')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', 'produk_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $kategori)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->whereNot('kategori_produk.aktif', 'T');
    }

    public static function sumTryout()
    {
        return Payment::sum('nominal');
    }

    public static function sumTryoutPerhari()
    {
        return Payment::whereDate('created_at', today())->sum('nominal');
    }

    public static function web()
    {
        return PengaturanWeb::all()->first();
    }
}
