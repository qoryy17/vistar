<?php

namespace App\Helpers;

use App\Models\OrderTryout;
use App\Models\Ujian;
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

    public static function hasilUjianBerbayar($customerId)
    {
        return Ujian::select('id', 'order_tryout_id', 'waktu_mulai', 'waktu_berakhir', 'sisa_waktu', 'status_ujian')
            ->where('status_ujian', 'Selesai')
            ->whereHas('order', function ($query) use ($customerId) {
                $query->where('status_order', 'paid');
                $query->where('customer_id', $customerId);
            })
            ->with('order', function ($query) {
                $query->select('id', 'produk_tryout_id', 'status_order');
                $query->with('tryout:id,nama_tryout');
            })
            ->with('hasil', function ($query) {
                $query->select('id', 'ujian_id', 'durasi_selesai', 'terjawab', 'tidak_terjawab', 'total_nilai', 'keterangan');
                $query->with('testimoni:id,hasil_ujian_id,testimoni,rating,publish');
                $query->with('passing_grade', function ($query) {
                    $query->select('id', 'hasil_ujian_id', 'alias', 'judul', 'passing_grade', 'terjawab', 'terlewati', 'benar', 'salah', 'total_nilai');
                    $query->orderBy('judul', 'DESC');
                });
            });
    }

    public static function pembelian($customer)
    {
        return DB::table('order_tryout')->select('order_tryout.*', 'produk_tryout.nama_tryout', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'pengaturan_tryout.masa_aktif')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->where('order_tryout.status_order', 'paid')
            ->where('customer_id', '=', $customer);
    }

    public static function hasilUjianGratis($customerId)
    {
        return Ujian::select('id', 'limit_tryout_id', 'waktu_mulai', 'waktu_berakhir', 'sisa_waktu', 'status_ujian')
            ->where('status_ujian', 'Selesai')
            ->whereHas('limit', function ($query) use ($customerId) {
                $query->where('customer_id', $customerId);
            })
            ->with('limit', function ($query) {
                $query->select('id', 'produk_tryout_id');
                $query->with('tryout:id,nama_tryout');
            })
            ->with('hasil', function ($query) {
                $query->select('id', 'ujian_id', 'durasi_selesai', 'terjawab', 'tidak_terjawab', 'total_nilai', 'keterangan');
                $query->with('testimoni:id,hasil_ujian_id,testimoni,rating,publish');
            });
    }

    public static function reportExam()
    {
        return DB::table('report_ujian')->select(
            'report_ujian.*',
            'produk_tryout.nama_tryout',
            'produk_tryout.kode_soal',
        )->leftJoin('produk_tryout', 'report_ujian.produk_tryout_id', 'produk_tryout.id');
    }
}
