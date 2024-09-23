<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\QueryCollect;
use App\Helpers\Waktu;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Site extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => Waktu::sesiWaktu() . ' ' . Auth::user()->name,
            'breadcumb' => 'Vi Star Indonesia',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'countPembelian' => QueryCollect::countPembelian(Auth::user()->customer_id),
            'tryoutTerbaru' => DB::table('produk_tryout')->select('produk_tryout.*', 'kategori_produk.status')->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')->where('kategori_produk.status', 'Berbayar')->orderBy('produk_tryout.created_at', 'DESC')->first(),
            'testimoni' => DB::table('testimoni')
                ->select('testimoni.id', 'testimoni.testimoni', 'testimoni.rating', 'produk_tryout.nama_tryout')
                ->leftJoin('produk_tryout', 'testimoni.produk_tryout_id', '=', 'produk_tryout.id')
                ->where('testimoni.publish', 'Y')
                ->orderBy('testimoni.updated_at', 'DESC')
                ->limit(3)
                ->get(),
        ];
        return view('customer-panel.home.beranda', $data);
    }

    public function tryoutBerbayar()
    {
        $data = [
            'page_title' => 'Tryout Berbayar',
            'breadcumb' => 'Tryout Berbayar',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'pembelian' => QueryCollect::pembelian(Auth::user()->customer_id),
            'hasilUjian' => QueryCollect::hasilUjianBerbayar(Auth::user()->customer_id)->orderBy('waktu_berakhir', 'DESC')->paginate(10),
        ];
        return view('customer-panel.tryout.tryout-berbayar', $data);
    }

    public function tryoutGratis()
    {
        $ujianGratis = DB::table('limit_tryout')
            ->select('limit_tryout.*', 'produk_tryout.nama_tryout', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'pengaturan_tryout.masa_aktif')
            ->leftJoin('produk_tryout', 'limit_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->where('status_validasi', 'Disetujui')
            ->where('customer_id', '=', Auth::user()->customer_id)
            ->get();

        $data = [
            'page_title' => 'Tryout Gratis',
            'breadcumb' => 'Tryout Gratis',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'ujianGratis' => $ujianGratis,
            'hasilUjian' => QueryCollect::hasilUjianGratis(Auth::user()->customer_id)->paginate(10),
        ];

        return view('customer-panel.tryout.tryout-gratis', $data);
    }

    public function eventTryout()
    {
        $data = [
            'page_title' => 'Event',
            'breadcumb' => 'Event Tryout',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
        ];
        return view('customer-panel.event.event-tryout-berbayar', $data);
    }

    public function generatePembelianData($filter): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Check Pending Transaction
        $paymentService = new PaymentService();
        $paymentService->checkPendingTransaction(Auth::user()->customer_id);

        $pembelian = DB::table('order_tryout')
            ->select(
                'order_tryout.*',
                'produk_tryout.nama_tryout',
                'produk_tryout.keterangan',
                'produk_tryout.status',
                'produk_tryout.kategori_produk_id',
                'kategori_produk.judul'
            )
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.status', 'Berbayar')
            ->where('order_tryout.customer_id', Auth::user()->customer_id);

        $pembelian->leftJoin('payment', 'order_tryout.id', '=', 'payment.ref_order_id')
            ->addSelect('payment.snap_token', 'payment.id as transaction_id', 'payment.nominal as total');

        $checkFilter = false;
        if (array_key_exists('category_id', $filter) && $filter['category_id']) {
            $checkFilter = true;
        }
        if (array_key_exists('year', $filter) && $filter['year']) {
            $checkFilter = true;
        }

        if ($checkFilter) {
            $pembelian->where(function ($query) use ($filter) {
                if (array_key_exists('category_id', $filter)) {
                    $query->where('kategori_produk.id', $filter['category_id']);
                }

                if (array_key_exists('year', $filter)) {
                    $query->whereYear('kategori_produk.created_at', $filter['year']);
                }
            });
        }

        $pembelian = $pembelian
            ->orderBy('order_tryout.created_at', 'DESC')
            ->paginate(10);

        return $pembelian;
    }

    public function pembelian()
    {
        $pembelian = $this->generatePembelianData([]);

        $transactionStatusList = Payment::$transactionStatus;

        $data = [
            'page_title' => 'Pembelian',
            'breadcumb' => 'Pembelian Produk',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'transactionStatusList' => $transactionStatusList,
            'transactions' => $pembelian,
        ];

        return view('customer-panel.tryout.pembelian', $data);
    }

    public function searchPembelian(Request $request)
    {
        $pembelian = $this->generatePembelianData([
            'category_id' => $request->input('category_id'),
            'year' => $request->input('tahun'),
        ]);

        $transactionStatusList = Payment::$transactionStatus;

        $data = [
            'page_title' => 'Pembelian',
            'breadcumb' => 'Pembelian Produk',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'search' => $pembelian,
            'transactionStatusList' => $transactionStatusList,
        ];
        return view('customer-panel.tryout.pembelian', $data);
    }
}
