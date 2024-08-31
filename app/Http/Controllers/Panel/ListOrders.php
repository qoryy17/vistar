<?php

namespace App\Http\Controllers\Panel;

use Carbon\Carbon;
use App\Helpers\Notifikasi;
use App\Models\LimitTryout;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use App\Helpers\QueryCollect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PDF;

class ListOrders extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'List Order',
            'bc1' => 'Manajemen Produk',
            'bc2' => 'List Order Tryout',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'order' => QueryCollect::dataOrder()
        ];
        return view('main-panel.order.data-order-tryout', $data);
    }

    public function detilOrder(Request $request)
    {
        $detilOrder = QueryCollect::detilDataOrder($request->orderID);
        $data = [
            'page_title' => $detilOrder->nama,
            'bc1' => 'Order',
            'bc2' => 'Detail Order ' . $detilOrder->nama,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'detilOrder' => $detilOrder
        ];
        return view('main-panel.order.detil-order', $data);
    }

    public function exportOrderToPDF(Request $request)
    {
        // Ambil tanggal dari request
        $tanggalAwal = Carbon::parse($request->input('tanggalAwal'))->format('Y-m-d');
        $tanggalAkhir = Carbon::parse($request->input('tanggalAkhir'))->format('Y-m-d');

        $order =  DB::table('order_tryout')->select(
            'order_tryout.*',
            'payment.ref_order_id',
            'payment.nominal',
            'payment.status_transaksi',
            'payment.metadata',
            'produk_tryout.nama_tryout'
        )->leftJoin('payment', 'order_tryout.payment_id', '=', 'payment.id')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->whereDate('order_tryout.created_at', '>=', $tanggalAwal)
            ->whereDate('order_tryout.created_at', '<=', $tanggalAkhir)->get();

        $data = [
            'title' => 'Tanggal Awal ' . Carbon::parse($tanggalAwal)->format('d-m-Y') . ' Tanggal Akhir : ' . Carbon::parse($tanggalAkhir)->format('d-m-Y'),
            'waktuCetak' => today(),
            'order' => $order
        ];
        $pdf = PDF::loadView('main-panel.order.report-order-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('LaporanRekap.pdf');
    }

    public function exportOrderToExcel(Request $request)
    {
        // Ambil tanggal dari request
        $tanggalAwal = Carbon::parse($request->input('tanggalAwal'))->format('Y-m-d');
        $tanggalAkhir = Carbon::parse($request->input('tanggalAkhir'))->format('Y-m-d');

        // Inisialisasi OrderExport dengan tanggal awal dan akhir
        $export = new OrderExport($tanggalAwal, $tanggalAkhir);

        // Ekspor ke Excel
        return $export->download('OrderRekap_' . date('dmY') . '.xlsx');
    }
}
