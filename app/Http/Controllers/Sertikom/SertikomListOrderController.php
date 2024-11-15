<?php

namespace App\Http\Controllers\Sertikom;

use App\Exports\OrderSertikomExport;
use PDF;
use Carbon\Carbon;
use App\Helpers\Notifikasi;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use App\Helpers\QueryCollect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SertikomListOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $titleForm = ucfirst($request->category);
        } elseif ($request->category == 'seminar') {
            $titleForm = ucfirst($request->category);
        } elseif ($request->category == 'workshop') {
            $titleForm = ucfirst($request->category);
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }

        $data = [
            'page_title' => 'List Order ' . $titleForm,
            'bc1' => 'Manajemen Sertikom',
            'bc2' => 'List Order ' . $titleForm,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'order' => QueryCollect::dataOrderSertikom($request->category),
            'category' => $request->category
        ];
        return view('main-panel.sertikom.data-order-sertikom', $data);
    }

    public function detailListOrderSertikom(Request $request)
    {
        $detilOrder = QueryCollect::detilDataOrderSertikom($request->id);
        $data = [
            'page_title' => $detilOrder->nama,
            'bc1' => 'Order ' . $detilOrder->judul,
            'bc2' => 'Detail Order ' . $detilOrder->nama,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'detilOrder' => $detilOrder,
            'category' => strtolower($detilOrder->judul)
        ];
        return view('main-panel.sertikom.detail-order-sertikom', $data);
    }

    public function exportOrderSertikomToPDF(Request $request)
    {
        // Ambil tanggal dari request
        $tanggalAwal = Carbon::parse($request->input('tanggalAwal'))->format('Y-m-d');
        $tanggalAkhir = Carbon::parse($request->input('tanggalAkhir'))->format('Y-m-d');
        $kategori = $request->input('kategori');

        $order = DB::table('order_pelatihan_seminar')->select(
            'order_pelatihan_seminar.*',
            'payment.ref_order_id',
            'payment.nominal',
            'payment.status_transaksi',
            'payment.metadata',
            'produk_pelatihan_seminar.produk',
            'kategori_produk.judul'
        )->leftJoin('payment', 'order_pelatihan_seminar.payment_id', '=', 'payment.id')
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $kategori)
            ->whereDate('order_pelatihan_seminar.created_at', '>=', $tanggalAwal)
            ->whereDate('order_pelatihan_seminar.created_at', '<=', $tanggalAkhir)->get();

        $data = [
            'title' => 'Tanggal Awal ' . Carbon::parse($tanggalAwal)->format('d-m-Y') . ' Tanggal Akhir : ' . Carbon::parse($tanggalAkhir)->format('d-m-Y'),
            'waktuCetak' => today(),
            'order' => $order,
            'category' => $request->category
        ];
        $pdf = PDF::loadView('main-panel.sertikom.report-order-sertikom-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('LaporanRekap-Order-Sertikom.pdf');
    }

    public function exportOrderSertikomToExcel(Request $request)
    {
        // Ambil tanggal dari request
        $tanggalAwal = Carbon::parse($request->input('tanggalAwal'))->format('Y-m-d');
        $tanggalAkhir = Carbon::parse($request->input('tanggalAkhir'))->format('Y-m-d');
        $kategori = $request->input('kategori');

        // Inisialisasi OrderSertikomExport dengan tanggal awal dan akhir serta kategori
        $export = new OrderSertikomExport($tanggalAwal, $tanggalAkhir, $kategori);

        // Ekspor ke Excel
        return $export->download('OrderRekapSertikom' . ucfirst($kategori) . '_' . date('dmY') . '.xlsx');
    }
}
