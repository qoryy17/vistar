<?php

namespace App\Helpers;

use App\Models\OrderTryout;
use App\Models\ProdukTryout;
use App\Models\Sertikom\OrderPelatihanSeminarModel;
use App\Models\Sertikom\ProdukPelatihanSeminarModel;
use App\Models\Ujian;
use Illuminate\Support\Facades\DB;

class QueryCollect
{
    public static function dataOrder()
    {
        return DB::table('order_tryout')->select('order_tryout.*', 'produk_tryout.nama_tryout')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')->get();
    }

    public static function dataOrderSertikom($category = false)
    {
        return DB::table('order_pelatihan_seminar')->select(
            'order_pelatihan_seminar.*',
            'produk_pelatihan_seminar.produk',
            'kategori_produk.judul',
        )->leftJoin(
            'produk_pelatihan_seminar',
            'order_pelatihan_seminar.produk_pelatihan_seminar_id',
            '=',
            'produk_pelatihan_seminar.id'
        )->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $category)
            ->get();
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

    public static function detilDataOrderSertikom($id = false)
    {
        return DB::table('order_pelatihan_seminar')->select(
            'order_pelatihan_seminar.*',
            'payment.ref_order_id',
            'payment.nominal',
            'payment.metode',
            'payment.status_transaksi',
            'payment.waktu_transaksi',
            'payment.metadata',
            'produk_pelatihan_seminar.produk',
            'kategori_produk.judul',
        )->leftJoin('payment', 'order_pelatihan_seminar.payment_id', '=', 'payment.id')
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('order_pelatihan_seminar.id', $id)
            ->first();
    }

    public static function newProductTryout()
    {
        return DB::table('produk_tryout')->select(
            'produk_tryout.*',
            'kategori_produk.status'
        )->leftJoin(
            'kategori_produk',
            'produk_tryout.kategori_produk_id',
            '=',
            'kategori_produk.id'
        )->where('kategori_produk.status', 'Berbayar')
            ->where('produk_tryout.status', 'Tersedia')
            ->orderBy('produk_tryout.created_at', 'DESC')->first();
    }

    public static function countBeliTryout($customer)
    {
        return OrderTryout::where('customer_id', $customer)->count();
    }

    public static function CountBeliSertikom($data)
    {
        $query = DB::table('order_pelatihan_seminar')
            ->select('order_pelatihan_seminar.*', 'produk_pelatihan_seminar.id as idProduk', 'kategori_produk.judul')
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $data['category'])
            ->where('order_pelatihan_seminar.customer_id', $data['customer'])->count();

        return $query;
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

    public static function reportExam($status)
    {
        $query = DB::table('report_ujian')
            ->select(
                'report_ujian.*',
                'produk_tryout.nama_tryout',
                'produk_tryout.kode_soal',
            )
            ->leftJoin('produk_tryout', 'report_ujian.produk_tryout_id', 'produk_tryout.id');

        if ($status) {
            $query = $query->where('report_ujian.status', $status);
        }

        return $query;
    }

    public static function examProducts()
    {
        return DB::table('order_tryout')->select(
            'order_tryout.id',
            'order_tryout.produk_tryout_id',
            'order_tryout.status_order',
            'order_tryout.khusus',
            'order_tryout.created_at',
            'order_tryout.updated_at',
            'produk_tryout.id as idProduk',
            'produk_tryout.nama_tryout'
        )->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->where('order_tryout.status_order', 'paid')->where('order_tryout.khusus', '1')
            ->groupBy(
                'order_tryout.id',
                'order_tryout.produk_tryout_id',
                'order_tryout.status_order',
                'order_tryout.khusus',
                'order_tryout.created_at',
                'order_tryout.updated_at',
                'produk_tryout.id',
                'produk_tryout.nama_tryout'
            )->orderBy('order_tryout.updated_at', 'desc')->get();
    }

    public static function sertikomProduct($category = null)
    {
        return DB::table('produk_pelatihan_seminar')->select(
            'produk_pelatihan_seminar.*',
            'topik_keahlian.topik',
            'topik_keahlian.deskripsi',
            'topik_keahlian.publish',
            'kategori_produk.judul'
        )->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('produk_pelatihan_seminar.publish', 'Y')
            ->where('kategori_produk.judul', $category)
            ->orderBy('produk_pelatihan_seminar.updated_at', 'desc')->get();
    }
    public static function newProductSertikom($category = null)
    {
        return DB::table('produk_pelatihan_seminar')->select(
            'produk_pelatihan_seminar.*',
            'topik_keahlian.topik',
            'topik_keahlian.deskripsi',
            'topik_keahlian.publish',
            'kategori_produk.judul'
        )->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('produk_pelatihan_seminar.publish', 'Y')
            ->where('kategori_produk.judul', $category)
            ->limit(1)
            ->orderBy('produk_pelatihan_seminar.updated_at', 'desc')->first();
    }

    public static function getOrderSertikom($data)
    {

        $query = DB::table('order_pelatihan_seminar')
            ->select(
                'order_pelatihan_seminar.*',
                'produk_pelatihan_seminar.id as idProduk',
                'produk_pelatihan_seminar.produk',
                'produk_pelatihan_seminar.harga',
                'topik_keahlian.topik',
                'kategori_produk.judul'
            )
            ->leftJoin(
                'produk_pelatihan_seminar',
                'order_pelatihan_seminar.produk_pelatihan_seminar_id',
                '=',
                'produk_pelatihan_seminar.id'
            )
            ->leftJoin(
                'topik_keahlian',
                'produk_pelatihan_seminar.topik_keahlian_id',
                '=',
                'topik_keahlian.id'
            )
            ->leftJoin(
                'kategori_produk',
                'produk_pelatihan_seminar.kategori_produk_id',
                '=',
                'kategori_produk.id'
            )
            ->where('kategori_produk.judul', $data['category'])
            ->where('order_pelatihan_seminar.customer_id', $data['customer'])->get();

        return $query;
    }

    public static function getDetailSertikom($data)
    {

        $query = DB::table('produk_pelatihan_seminar')
            ->select(
                'produk_pelatihan_seminar.*',
                'topik_keahlian.topik',
                'instruktur.instruktur',
                'instruktur.keahlian',
                'instruktur.deskripsi as deskripsi_instruktur',
                'kategori_produk.judul'
            )
            ->leftJoin(
                'topik_keahlian',
                'produk_pelatihan_seminar.topik_keahlian_id',
                '=',
                'topik_keahlian.id'
            )
            ->leftJoin(
                'instruktur',
                'produk_pelatihan_seminar.instruktur_id',
                '=',
                'instruktur.id'
            )
            ->leftJoin(
                'kategori_produk',
                'produk_pelatihan_seminar.kategori_produk_id',
                '=',
                'kategori_produk.id'
            )
            ->where('kategori_produk.judul', $data['category'])
            ->where('produk_pelatihan_seminar.id', $data['id'])
            ->first();

        return $query;
    }
}
