<?php

namespace App\Helpers;

use App\Models\OrderTryout;
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
            'payment.metode',
            'payment.status_transaksi',
            'payment.waktu_transaksi',
            'payment.metadata',
            'produk_tryout.nama_tryout'
        )->leftJoin('payment', 'order_tryout.payment_id', '=', 'payment.id')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->where('order_tryout.id', $id)
            ->first();
    }

    public static function countPembelian($customer)
    {
        return OrderTryout::where('customer_id', $customer)->count();
    }

    public static function hasilUjianBerbayar($customer)
    {
        return
            DB::table('hasil_ujian')
            ->select(
                'hasil_ujian.id',
                'hasil_ujian.durasi_selesai',
                'hasil_ujian.benar',
                'hasil_ujian.salah',
                'hasil_ujian.terjawab',
                'hasil_ujian.tidak_terjawab',
                'hasil_ujian.total_nilai as skd',
                'hasil_ujian.keterangan',
                'ujian.id as ujianID',
                'ujian.waktu_mulai',
                'ujian.sisa_waktu',
                'ujian.status_ujian',
                'order_tryout.customer_id',
                'order_tryout.produk_tryout_id',
                'order_tryout.status_order'
            )->leftJoin('ujian', 'hasil_ujian.ujian_id', '=', 'ujian.id')
            ->leftJoin('order_tryout', 'ujian.order_tryout_id', '=', 'order_tryout.id')
            ->where('ujian.status_ujian', 'Selesai')
            ->where('order_tryout.status_order', 'paid')
            ->where('order_tryout.customer_id', $customer);
    }

    public static function pembelian($customer)
    {
        return DB::table('order_tryout')->select('order_tryout.*', 'produk_tryout.nama_tryout', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'pengaturan_tryout.masa_aktif')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->where('order_tryout.status_order', 'paid')
            ->where('customer_id', '=', $customer);
    }

    public static function hasilUjianGratis($customer)
    {
        return DB::table('hasil_ujian')
            ->select(
                'hasil_ujian.id',
                'hasil_ujian.durasi_selesai',
                'hasil_ujian.benar',
                'hasil_ujian.salah',
                'hasil_ujian.terjawab',
                'hasil_ujian.tidak_terjawab',
                'hasil_ujian.total_nilai as skd',
                'hasil_ujian.keterangan',
                'ujian.id as ujianID',
                'ujian.waktu_mulai',
                'ujian.sisa_waktu',
                'ujian.status_ujian',
                'limit_tryout.customer_id',
                'limit_tryout.produk_tryout_id'
            )->leftJoin('ujian', 'hasil_ujian.ujian_id', '=', 'ujian.id')
            ->leftJoin('limit_tryout', 'ujian.limit_tryout_id', '=', 'limit_tryout.id')
            ->where('ujian.status_ujian', 'Selesai')
            ->where('limit_tryout.customer_id', $customer);
    }
}
