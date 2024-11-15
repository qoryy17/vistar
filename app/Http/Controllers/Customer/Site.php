<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\Waktu;
use App\Models\Payment;
use App\Models\Customer;
use App\Helpers\BerandaUI;
use Illuminate\Http\Request;
use App\Helpers\QueryCollect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentSertikomService;

class Site extends Controller
{
    public function index()
    {
        $web = BerandaUI::web();
        $data = [
            'page_title' => Waktu::sesiWaktu() . ' ' . Auth::user()->name,
            'breadcumb' => $web->nama_bisnis,
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'countTryout' => QueryCollect::countBeliTryout(Auth::user()->customer_id),
            'countPelatihan' => QueryCollect::CountBeliSertikom(['customer' => Auth::user()->customer_id, 'category' => 'Pelatihan']),
            'countSeminar' => QueryCollect::CountBeliSertikom(['customer' => Auth::user()->customer_id, 'category' => 'Seminar']),
            'countWorkshop' => QueryCollect::CountBeliSertikom(['customer' => Auth::user()->customer_id, 'category' => 'Workshop']),
            'tryoutTerbaru' => QueryCollect::newProductTryout(),
            'trainingTerbaru' => QueryCollect::newProductSertikom('Pelatihan'),
            'seminarTerbaru' => QueryCollect::newProductSertikom('Seminar'),
            'workshopTerbaru' => QueryCollect::newProductSertikom('Workshop'),
            'testimoniTryout' => DB::table('testimoni')
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
            'pembelian' => QueryCollect::pembelian(Auth::user()->customer_id)->get(),
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

    // Add Sertikom Product Pelatihan, Seminar/Workshop
    public function generateOrderSertikom($filter): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Check Pending Transaction
        $paymentService = new PaymentSertikomService();
        $paymentService->checkPendingTransaction(Auth::user()->customer_id);

        $pembelian = DB::table('order_pelatihan_seminar')
            ->select(
                'order_pelatihan_seminar.*',
                'produk_pelatihan_seminar.produk',
                'produk_pelatihan_seminar.deskripsi',
                'produk_pelatihan_seminar.publish',
                'produk_pelatihan_seminar.instruktur_id',
                'produk_pelatihan_seminar.kategori_produk_id',
                'produk_pelatihan_seminar.topik_keahlian_id',
                'kategori_produk.judul',
                'kategori_produk.status as kategori_status',
                'topik_keahlian.topik',
                'topik_keahlian.publish as topik_keahlian_publish'
            )
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->where('kategori_produk.status', 'Berbayar')
            ->where('topik_keahlian.publish', 'Y')
            ->where('order_pelatihan_seminar.customer_id', Auth::user()->customer_id);

        $pembelian->leftJoin('payment', 'order_pelatihan_seminar.id', '=', 'payment.ref_order_id')
            ->addSelect('payment.snap_token', 'payment.id as transaction_id', 'payment.nominal as total');

        $checkFilter = false;

        if (array_key_exists('category_id', $filter) && $filter['category_id']) {
            $checkFilter = true;
        }

        if (array_key_exists('expertise_id', $filter) && $filter['expertise_id']) {
            $checkFilter = true;
        }

        if (array_key_exists('year', $filter) && $filter['year']) {
            $checkFilter = true;
        }

        if ($checkFilter) {
            $pembelian->where(function ($query) use ($filter) {

                if (array_key_exists('expertise_id', $filter)) {
                    $query->where('kategori_produk.id', $filter['expertise_id']);
                }

                if (array_key_exists('category_id', $filter)) {
                    $query->where('kategori_produk.id', $filter['category_id']);
                }

                if (array_key_exists('year', $filter)) {
                    $query->whereYear('kategori_produk.created_at', $filter['year']);
                }
            });
        }

        $pembelian = $pembelian
            ->where('kategori_produk.judul', $filter['category'])
            ->orderBy('order_pelatihan_seminar.created_at', 'DESC')
            ->paginate(10);

        return $pembelian;
    }

    public function orderProductSertikom(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $viewPage = 'customer-panel.sertikom.pembelian-pelatihan';
        } elseif ($request->category == 'seminar') {
            $viewPage = 'customer-panel.sertikom.pembelian-seminar';
        } elseif ($request->category == 'workshop') {
            $viewPage = 'customer-panel.sertikom.pembelian-workshop';
        } else {
            return redirect()->route('site.main')->with('error', 'Kategori tidak ditemukan !');
        }

        $pembelian = $this->generateOrderSertikom(['category' => $request->category]);

        $transactionStatusList = Payment::$transactionStatus;

        $data = [
            'page_title' => 'Pembelian',
            'breadcumb' => 'Pembelian ' . ucfirst($request->category),
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'transactionStatusList' => $transactionStatusList,
            'transactions' => $pembelian,
        ];

        return view($viewPage, $data);
    }

    public function searchOrderSertikom(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $viewPage = 'customer-panel.sertikom.pembelian-pelatihan';
        } elseif ($request->category == 'seminar') {
            $viewPage = 'customer-panel.sertikom.pembelian-seminar';
        } elseif ($request->category == 'workshop') {
            $viewPage = 'customer-panel.sertikom.pembelian-workshop';
        } else {
            return redirect()->route('site.main')->with('error', 'Kategori tidak ditemukan !');
        }

        $pembelian = $this->generateOrderSertikom([
            'category_id' => $request->input('category_id'),
            'expertise_id' => $request->input('expertise_id'),
            'year' => $request->input('tahun'),
        ]);

        $transactionStatusList = Payment::$transactionStatus;

        $data = [
            'page_title' => 'Pembelian',
            'breadcumb' => 'Pembelian ' . ucfirst($request->category),
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'search' => $pembelian,
            'transactionStatusList' => $transactionStatusList,
        ];
        return view($viewPage, $data);
    }

    public function certificateSertikom(Request $request) {}

    public function checkCertificate(Request $request) {}
}
