<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class QueryCollect
{
    public static function dataOrder()
    {
        return DB::table('order_tryout')->select('order_tryout.*', 'produk_tryout.nama_tryout')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')->get();
    }

    public static function detilDataOrder($id = false)
    {
        return DB::table('order_tryout')->select(
            'order_tryout.*',
            'payment.ref_order_id',
            'payment.nominal',
            'payment.status_transaksi',
            'payment.metadata',
            'produk_tryout.nama_tryout'
        )->leftJoin('payment', 'order_tryout.payment_id', '=', 'payment.id')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->where('order_tryout.id', $id)
            ->first();
    }
}
