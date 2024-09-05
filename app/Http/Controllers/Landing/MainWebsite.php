<?php

namespace App\Http\Controllers\Landing;

use App\Models\Customer;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use App\Models\KeranjangOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\BerandaUI;
use App\Models\OrderTryout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class MainWebsite extends Controller
{
    public function index()
    {
        $testimoni = DB::table('testimoni')->select(
            'testimoni.*',
            'customer.nama_lengkap',
            'customer.pendidikan',
            'customer.jurusan',
            'customer.foto'
        )->leftJoin('customer', 'testimoni.customer_id', '=', 'customer.id')
            ->where('publish', 'Y')->orderBy('updated_at', 'desc')->limit(10);

        $web = BerandaUI::web();

        $data  = [
            'title' => $web->nama_bisnis . " " . $web->tagline,
            'testimoni' => $testimoni,
            'web' => $web
        ];
        return view('main-web.home.beranda', $data);
    }

    public function produkBerbayar()
    {
        $data  = [
            'title' => 'Produk Paket Tryout',
            'kategoriProduk' => KategoriProduk::all()->where('aktif', 'Y')->where('status', 'Berbayar'),
            'searchpaketTryout' => '',
            'searchcariPaket' => '',
            'allProduk' => DB::table('produk_tryout')->select(
                'produk_tryout.*',
                'pengaturan_tryout.harga',
                'pengaturan_tryout.nilai_keluar',
                'pengaturan_tryout.grafik_evaluasi',
                'pengaturan_tryout.review_pembahasan',
                'pengaturan_tryout.masa_aktif',
                'pengaturan_tryout.harga_promo',
                'kategori_produk.judul',
                'kategori_produk.status as produk_status'
            )->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_tryout.status', 'Tersedia')
                ->whereNot('kategori_produk.status', 'Gratis')->orderBy('produk_tryout.updated_at', 'DESC')->get()
        ];
        return view('main-web.produk.tryout-berbayar', $data);
    }

    public function produkGratis()
    {
        // Cek apakah sudah pilih produk tryout gratis
        $cekGratisan = LimitTryout::where('customer_id', Auth::user()->customer_id)->where('status_validasi', 'Disetujui')->first();
        if ($cekGratisan) {
            if ($cekGratisan->produk_tryout_id != null) {
                return redirect()->route('site.tryout-gratis');
            }
        } else {
            return redirect()->route('mainweb.index', '#coba-gratis');
        }

        $data  = [
            'title' => 'Produk Paket Tryout Gratis',
            'kategoriProduk' => KategoriProduk::all()->where('aktif', 'Y')->where('status', 'Gratis'),
            'searchpaketTryout' => '',
            'searchcariPaket' => '',
            'allProduk' => DB::table('produk_tryout')->select(
                'produk_tryout.*',
                'pengaturan_tryout.harga',
                'pengaturan_tryout.nilai_keluar',
                'pengaturan_tryout.grafik_evaluasi',
                'pengaturan_tryout.review_pembahasan',
                'pengaturan_tryout.masa_aktif',
                'pengaturan_tryout.harga_promo',
                'kategori_produk.judul',
                'kategori_produk.status as produk_status'
            )->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')->whereNot('kategori_produk.status', 'Berbayar')->orderBy('produk_tryout.updated_at', 'DESC')->get()
        ];
        return view('main-web.produk.tryout-gratis', $data);
    }

    public function searchProdukBerbayar(Request $request)
    {
        if ($request->paketTryout) {
            $query = DB::table('produk_tryout')->select('produk_tryout.*', 'pengaturan_tryout.harga', 'pengaturan_tryout.nilai_keluar', 'pengaturan_tryout.grafik_evaluasi', 'pengaturan_tryout.review_pembahasan', 'pengaturan_tryout.masa_aktif', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status as produk_status')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_tryout.status', 'Tersedia')
                ->where('kategori_produk.judul', '=', $request->input('paketTryout'))->whereNot('kategori_produk.status', 'Gratis')->orderBy('produk_tryout.updated_at', 'DESC')->get();
        } elseif ($request->cariPaket) {
            $query = DB::table('produk_tryout')->select('produk_tryout.*', 'pengaturan_tryout.harga', 'pengaturan_tryout.nilai_keluar', 'pengaturan_tryout.grafik_evaluasi', 'pengaturan_tryout.review_pembahasan', 'pengaturan_tryout.masa_aktif', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status as produk_status')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_tryout.status', 'Tersedia')
                ->whereLike('produk_tryout.nama_tryout', "%{$request->input('cariPaket')}%")->whereNot('kategori_produk.status', 'Gratis')->orderBy('produk_tryout.updated_at', 'DESC')->get();
        } else {
            return Redirect::to('/produk-berbayar');
        }
        $data  = [
            'title' => 'Produk Paket Tryout',
            'kategoriProduk' => KategoriProduk::all()->where('aktif', 'Y')->where('status', 'Berbayar'),
            'allProduk' => $query,
            'searchpaketTryout' => $request->paketTryout,
            'searchcariPaket' => $request->cariPaket,
        ];
        return view('main-web.produk.tryout-berbayar', $data);
    }

    public function searchProdukGratis(Request $request)
    {
        // Cek apakah sudah pilih produk tryout gratis
        $cekGratisan = LimitTryout::where('customer_id', Auth::user()->customer_id)->first();
        if ($cekGratisan->produk_tryout_id != null) {
            return redirect()->route('site.tryout-gratis');
        }

        if ($request->paketTryout) {
            $query = DB::table('produk_tryout')->select('produk_tryout.*', 'pengaturan_tryout.harga', 'pengaturan_tryout.nilai_keluar', 'pengaturan_tryout.grafik_evaluasi', 'pengaturan_tryout.review_pembahasan', 'pengaturan_tryout.masa_aktif', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status as produk_status')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('kategori_produk.judul', '=', $request->input('paketTryout'))->whereNot('kategori_produk.status', 'Berbayar')->orderBy('produk_tryout.updated_at', 'DESC')->get();
        } elseif ($request->cariPaket) {
            $query = DB::table('produk_tryout')->select('produk_tryout.*', 'pengaturan_tryout.harga', 'pengaturan_tryout.nilai_keluar', 'pengaturan_tryout.grafik_evaluasi', 'pengaturan_tryout.review_pembahasan', 'pengaturan_tryout.masa_aktif', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status as produk_status')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->whereLike('produk_tryout.nama_tryout', "%{$request->input('cariPaket')}%")->whereNot('kategori_produk.status', 'Berbayar')->orderBy('produk_tryout.updated_at', 'DESC')->get();
        } else {
            return Redirect::to('/produk-gratis');
        }
        $data  = [
            'title' => 'Produk Paket Tryout Gratis',
            'kategoriProduk' => KategoriProduk::all()->where('aktif', 'Y')->where('status', 'Gratis'),
            'allProduk' => $query,
            'searchpaketTryout' => $request->paketTryout,
            'searchcariPaket' => $request->cariPaket,
        ];
        return view('main-web.produk.tryout-gratis', $data);
    }

    public function pesanTryoutBerbayar(Request $request): RedirectResponse
    {
        // Check apakah pernah memesan produk yang sama
        $tryout = OrderTryout::where('produk_tryout_id', Crypt::decrypt($request->idProdukTryout))->where('customer_id', Auth::user()->customer_id)->first();
        $keranjang = KeranjangOrder::where('produk_tryout_id', Crypt::decrypt($request->idProdukTryout))->where('customer_id', Auth::user()->customer_id)->first();
        if ($tryout) {
            return Redirect::route('mainweb.keranjang')->with('errorMessage', 'Tidak dapat memesan produk yang sama sebelumnya !');
        } elseif ($keranjang) {
            return Redirect::route('mainweb.keranjang')->with('errorMessage', 'Tidak dapat menambahkan produk yang sama pada keranjang pesanan !');
        }

        $keranjangOrder = new KeranjangOrder();
        $keranjangOrder->id = rand(1, 99) . rand(1, 999);
        $keranjangOrder->produk_tryout_id = Crypt::decrypt($request->idProdukTryout);
        $keranjangOrder->customer_id = Auth::user()->customer_id;

        if ($keranjangOrder->save()) {
            return redirect()->route('mainweb.keranjang');
        } else {
            return redirect()->back()->with('errorMessage', 'Produk gagal ditambahkan dikeranjang !');
        }
    }

    public function keranjangPesanan()
    {
        $data  = [
            'title' => 'Keranjang Pesanan',
            'tryout' =>  DB::table('keranjang_order')->select('keranjang_order.*', 'produk_tryout.id as idProduk', 'produk_tryout.nama_tryout', 'produk_tryout.keterangan', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status')
                ->leftJoin('produk_tryout', 'keranjang_order.produk_tryout_id', '=', 'produk_tryout.id')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('keranjang_order.customer_id', '=', Auth::user()->customer_id)
                ->whereNot('kategori_produk.status', 'Gratis')->orderBy('keranjang_order.updated_at', 'DESC'),
            'allProduk' => DB::table('produk_tryout')->select(
                'produk_tryout.*',
                'pengaturan_tryout.harga',
                'pengaturan_tryout.nilai_keluar',
                'pengaturan_tryout.grafik_evaluasi',
                'pengaturan_tryout.review_pembahasan',
                'pengaturan_tryout.masa_aktif',
                'pengaturan_tryout.harga_promo',
                'kategori_produk.judul',
                'kategori_produk.status as produk_status'
            )->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_tryout.status', 'Tersedia')
                ->whereNot('kategori_produk.status', 'Gratis')->orderBy('produk_tryout.updated_at', 'DESC')->get()
        ];

        return view('main-web.produk.keranjang-order', $data);
    }

    public function hapusItemPesanan(Request $request): RedirectResponse
    {
        $itemTryout = KeranjangOrder::findOrFail(Crypt::decrypt($request->id));
        if ($itemTryout) {
            $itemTryout->delete();
            return redirect()->route('mainweb.keranjang')->with('successMessage', 'Item pesanan berhasil dihapus !');
        } else {
            return redirect()->route('mainweb.keranjang')->with('errorMessage', 'Item pesanan gagal dihapus !');
        }
    }

    public function daftarTryoutGratis()
    {
        // Cek apakah sudah pernah mengajukan permohonan
        $cekGratisan = LimitTryout::where('customer_id', Auth::user()->customer_id)->where('status_validasi', 'Disetujui')->first();
        if ($cekGratisan) {
            if ($cekGratisan->produk_tryout_id != null) {
                return redirect()->route('site.tryout-gratis');
            }
            return redirect()->route('mainweb.produk-gratis');
        }
        $data  = [
            'title' => 'Coba Tryout Gratis',
            'customer' => Customer::all()->where('id', Auth::user()->customer_id)->first()
        ];
        return view('main-web.produk.daftar-tryout-gratis', $data);
    }

    public function profil()
    {
        $data  = [
            'title' => 'Profil Saya',
            'customer' => Customer::findOrFail(Auth::user()->customer_id)
        ];
        return view('main-web.profil.profil', $data);
    }

    public function kebijakanPrivasi()
    {
        $data  = [
            'title' => 'Kebijakan Privasi',
            'web' => BerandaUI::web()
        ];
        return view('main-web.tentang.kebijakan-privasi', $data);
    }

    public function tentang()
    {
        $data  = [
            'title' => 'Tentang Vistar Indonesia',
            'web' => BerandaUI::web()
        ];
        return view('main-web.tentang.tentang', $data);
    }

    public function kontak()
    {
        $data  = [
            'title' => 'Kontak',
            'web' => BerandaUI::web()
        ];
        return view('main-web.tentang.kontak', $data);
    }
}
