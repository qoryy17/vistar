<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Notifikasi
{

    public static function tryoutGratis()
    {
        return DB::table('limit_tryout')->select('limit_tryout.*', 'customer.nama_lengkap', 'produk_tryout.nama_tryout', 'kategori_produk.status')
            ->leftJoin('customer', 'limit_tryout.customer_id', '=', 'customer.id')
            ->leftJoin('produk_tryout', 'limit_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('status_validasi', 'Menunggu')->get();
    }
}
