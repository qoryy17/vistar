<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\OrderTryout;
use App\Models\Payment;
use App\Models\PengaturanWeb;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BerandaUI
{
    public static function customerTerdaftar()
    {
        return Customer::count();
    }

    public static function customerTerdaftarPerhari()
    {
        return Customer::whereDate('created_at', today())->count();
    }

    public static function tryoutTerjual()
    {
        return OrderTryout::where('status_order', 'paid')->count();
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

    public static function statistikSertikom($kategori = false)
    {
        return DB::table('order_pelatihan_seminar')->select('order_pelatihan_seminar.*', 'produk_pelatihan_seminar.kategori_produk_id', 'kategori_produk.judul', 'kategori_produk.status', 'kategori_produk.aktif')
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', 'produk_pelatihan_seminar.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $kategori)
            ->where('order_pelatihan_seminar.status_order', 'paid')
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
        return Cache::remember('app:web_setting', 7 * 24 * 60 * 60, function () {
            return PengaturanWeb::first();
        });
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
            ->where('order_tryout.status_order', 'paid')
            ->groupBy(DB::raw('MONTH(order_tryout.created_at)'))
            ->orderBy(DB::raw('MONTH(order_tryout.created_at)'))
            ->pluck('total_orders', 'month');
    }

    public static function countOrderSertikom($kategori, $tahun)
    {
        return DB::table('order_pelatihan_seminar')
            ->select(
                DB::raw('MONTH(order_pelatihan_seminar.created_at) as month'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $kategori)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->whereNot('kategori_produk.aktif', 'T')
            ->whereYear('order_pelatihan_seminar.created_at', $tahun)
            ->where('order_pelatihan_seminar.status_order', 'paid')
            ->groupBy(DB::raw('MONTH(order_pelatihan_seminar.created_at)'))
            ->orderBy(DB::raw('MONTH(order_pelatihan_seminar.created_at)'))
            ->pluck('total_orders', 'month');
    }

    public static function reportExam()
    {
        return DB::table('report_ujian')->select(
            'report_ujian.*',
            'produk_tryout.nama_tryout',
            'produk_tryout.kode_soal',
        )->leftJoin('produk_tryout', 'report_ujian.produk_tryout_id', 'produk_tryout.id')->where('report_ujian.status', 'Waiting');
    }

    public static function benefitTraining()
    {
        $benefit = [
            'E-Sertifikat Pelatihan & Kehadiran',
            'Modul Pelatihan',
            'Video Rekaman Pelatihan',
            'Grup Alumni Pelatihan',
            'Experince'
        ];

        return $benefit;
    }

    public static function benefitSeminar()
    {
        $benefit = [
            'E-Sertifikat Kehadiran',
            'Video Rekaman',
            'Grup Alumni & Relasi'
        ];

        return $benefit;
    }

    public static function benefitWorkshop()
    {
        $benefit = [
            'E-Sertifikat Pelatihan & Kehadiran',
            'Modul Pelatihan',
            'Video Rekaman Pelatihan',
            'Grup Alumni Pelatihan',
            'Experince'
        ];

        return $benefit;
    }
}
