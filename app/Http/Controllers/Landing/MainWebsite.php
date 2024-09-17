<?php

namespace App\Http\Controllers\Landing;

use App\Helpers\BerandaUI;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\KategoriProduk;
use App\Models\KeranjangOrder;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use App\Models\ProdukTryout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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

        // This is should from the database
        $productCategories = [
            [
                'id' => 5550,
                'title' => 'PPPK',
                'price' => 50000,
                'is_popular' => false,
                'features' => [
                    'Ujian Tidak Terbatas',
                    'Hasil Ujian',
                    'Grafik Hasil Ujian',
                    'Review Pembahasan Soal',
                    // 'Akses Bagikan Referal'
                ],
            ],
            [
                'id' => 19571,
                'title' => 'CPNS',
                'price' => 50000,
                'is_popular' => true,
                'features' => [
                    'Ujian Tidak Terbatas',
                    'Hasil Ujian',
                    'Grafik Hasil Ujian',
                    'Review Pembahasan Soal',
                ],
            ],
            [
                'id' => 86539,
                'title' => 'Kedinasan',
                'price' => 50000,
                'is_popular' => false,
                'features' => [
                    'Ujian Tidak Terbatas',
                    'Hasil Ujian',
                    'Grafik Hasil Ujian',
                    'Review Pembahasan Soal',
                ],
            ],
        ];

        $data = [
            'title' => $web->nama_bisnis . " " . $web->tagline,
            'testimoni' => $testimoni,
            'web' => $web,
            'productCategories' => $productCategories,
        ];

        return view('main-web.home.beranda', $data);
    }

    public function products(Request $request)
    {
        $title = 'Produk Paket Tryout';
        $searchCategoryId = $request->category_id;
        $searchName = $request->search_name;

        if ($searchCategoryId || $searchName) {
            $title = 'Cari Produk Paket Tryout';
        }

        $productStatus = 'Berbayar';
        $products = Cache::remember('products_main_web:category_id:' . $searchCategoryId . ',status:' . $productStatus . ',search:' . $searchName, 7 * 24 * 60 * 60, function () use ($searchCategoryId, $productStatus, $searchName) {
            $data = DB::table('produk_tryout')
                ->select(
                    'produk_tryout.*',
                    'pengaturan_tryout.harga',
                    'pengaturan_tryout.nilai_keluar',
                    'pengaturan_tryout.grafik_evaluasi',
                    'pengaturan_tryout.review_pembahasan',
                    'pengaturan_tryout.masa_aktif',
                    'pengaturan_tryout.harga_promo',
                    'kategori_produk.judul',
                    'kategori_produk.status as produk_status'
                )
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_tryout.status', 'Tersedia')
                ->where('kategori_produk.status', $productStatus)
                ->orderBy('produk_tryout.updated_at', 'DESC');

            if ($searchCategoryId) {
                $data = $data->where('kategori_produk.id', '=', $searchCategoryId);
            }
            if ($searchName) {
                $data = $data->whereLike('produk_tryout.nama_tryout', "%{$searchName}%");
            }

            return $data->get();
        });

        $categoryStatus = 'Berbayar';
        $categoryActive = 'Y';
        $categories = Cache::remember('product_categories_main_web:status:' . $categoryStatus . ',active:' . $categoryActive, 7 * 24 * 60 * 60, function () use ($categoryStatus, $categoryActive) {
            return KategoriProduk::where('aktif', $categoryActive)
                ->select('id', 'judul')
                ->where('status', $categoryStatus)
                ->get();
        });

        $data = [
            'title' => $title,
            'categories' => $categories,
            'products' => $products,
            'searchCategoryId' => $searchCategoryId,
            'searchName' => $searchName,
        ];
        return view('main-web.produk.tryout-berbayar', $data);
    }

    public function productShow(int $id)
    {
        $product = Cache::remember('product_show_main_web:' . $id, 7 * 24 * 60 * 60, function () use ($id) {
            $data = ProdukTryout::where('id', $id)
                ->select(
                    'id',
                    'nama_tryout',
                    'keterangan',
                    'thumbnail',
                    'kategori_produk_id',
                    'pengaturan_tryout_id',
                )
                ->with('category', function ($query) {
                    $query->select('id', 'judul', 'status');
                })
                ->with('setting', function ($query) {
                    $query->select('id', 'harga', 'harga_promo', 'durasi', 'nilai_keluar', 'grafik_evaluasi', 'review_pembahasan', 'ulang_ujian', 'masa_aktif');
                });

            return $data->first();
        });

        if (!$product) {
            return redirect()->route('mainweb.product')->with(['errorMessage' => 'Produk tidak ditemukan, silahkan pilih produk yang tersedia']);
        }

        $order = null;
        if (Auth::check()) {
            $order = OrderTryout::where('produk_tryout_id', $product->id)
                ->where('customer_id', Auth::user()->customer_id)
                ->where('status_order', 'paid')
                ->first();
        }

        $title = 'Produk - ' . $product->nama_tryout;

        $productStatus = 'Berbayar';
        $recommendProducts = Cache::remember('products_main_web:except:' . $id . ',status:' . $productStatus, 7 * 24 * 60 * 60, function () use ($id, $productStatus) {
            $data = DB::table('produk_tryout')
                ->select(
                    'produk_tryout.*',
                    'pengaturan_tryout.harga',
                    'pengaturan_tryout.nilai_keluar',
                    'pengaturan_tryout.grafik_evaluasi',
                    'pengaturan_tryout.review_pembahasan',
                    'pengaturan_tryout.masa_aktif',
                    'pengaturan_tryout.harga_promo',
                    'kategori_produk.judul',
                    'kategori_produk.status as produk_status'
                )
                ->where('produk_tryout.id', '!=', $id)
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_tryout.status', 'Tersedia')
                ->where('kategori_produk.status', $productStatus)
                ->orderBy('produk_tryout.updated_at', 'DESC');

            return $data->limit(3)->get();
        });

        $data = [
            'title' => $title,
            'product' => $product,
            'order' => $order,
            'recommendProducts' => $recommendProducts,
        ];
        return view('main-web.produk.tryout-show', $data);
    }

    public function freeProducts(Request $request)
    {
        $title = 'Produk Paket Tryout Gratis';
        $searchCategoryId = $request->category_id;
        $searchName = $request->search_name;

        // Cek apakah sudah pilih produk tryout gratis
        $cekGratisan = LimitTryout::where('customer_id', Auth::user()->customer_id)->where('status_validasi', 'Disetujui')->orderBy('created_at', 'ASC')->get();
        if ($cekGratisan->count() > 0) {
            // Check if all of limit tryout already has produk_tryout_id
            $exists = false;
            foreach ($cekGratisan as $limit) {
                if ($limit->produk_tryout_id === null) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                return redirect()->route('site.tryout-gratis');
            }
        } else {
            return redirect()->route('mainweb.index', '#coba-gratis');
        }

        if ($searchCategoryId || $searchName) {
            $title = 'Cari Produk Paket Tryout Gratis';
        }

        $productStatus = 'Gratis';
        $products = Cache::remember('products_main_web:category_id:' . $searchCategoryId . ',status:' . $productStatus . ',search:' . $searchName, 7 * 24 * 60 * 60, function () use ($searchCategoryId, $productStatus, $searchName) {
            $data = DB::table('produk_tryout')
                ->select(
                    'produk_tryout.*',
                    'pengaturan_tryout.harga',
                    'pengaturan_tryout.nilai_keluar',
                    'pengaturan_tryout.grafik_evaluasi',
                    'pengaturan_tryout.review_pembahasan',
                    'pengaturan_tryout.masa_aktif',
                    'pengaturan_tryout.harga_promo',
                    'kategori_produk.judul',
                    'kategori_produk.status as produk_status'
                )
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_tryout.status', 'Tersedia')
                ->where('kategori_produk.status', $productStatus)
                ->orderBy('produk_tryout.updated_at', 'DESC');

            if ($searchCategoryId) {
                $data = $data->where('kategori_produk.id', '=', $searchCategoryId);
            }
            if ($searchName) {
                $data = $data->whereLike('produk_tryout.nama_tryout', "%{$searchName}%");
            }

            return $data->get();
        });

        $categoryStatus = 'Gratis';
        $categoryActive = 'Y';
        $categories = Cache::remember('product_categories_main_web:status:' . $categoryStatus . ',active:' . $categoryActive, 7 * 24 * 60 * 60, function () use ($categoryStatus, $categoryActive) {
            return KategoriProduk::where('aktif', $categoryActive)
                ->select('id', 'judul')
                ->where('status', $categoryStatus)
                ->get();
        });

        $data = [
            'title' => $title,
            'categories' => $categories,
            'searchCategoryId' => $searchCategoryId,
            'searchName' => $searchName,
            'products' => $products,
        ];
        return view('main-web.produk.tryout-gratis', $data);
    }

    public function pesanTryoutBerbayar(Request $request): RedirectResponse
    {
        // Check apakah pernah memesan produk yang sama
        $tryout = OrderTryout::where('produk_tryout_id', Crypt::decrypt($request->idProdukTryout))->where('status_order', 'paid')->where('customer_id', Auth::user()->customer_id)->first();
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
        $data = [
            'title' => 'Keranjang Pesanan',
            'tryout' => DB::table('keranjang_order')->select('keranjang_order.*', 'produk_tryout.id as idProduk', 'produk_tryout.nama_tryout', 'produk_tryout.keterangan', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status')
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
                ->whereNot('kategori_produk.status', 'Gratis')->orderBy('produk_tryout.updated_at', 'DESC')->limit(3)->get(),
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
        $cekGratisan = LimitTryout::where('customer_id', Auth::user()->customer_id)->where('status_validasi', 'Disetujui')->get();
        if ($cekGratisan->count() > 0) {
            // Check if all of limit tryout already has produk_tryout_id
            $exists = false;
            foreach ($cekGratisan as $limit) {
                if ($limit->produk_tryout_id === null) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                return redirect()->route('site.tryout-gratis');
            }

            return redirect()->route('mainweb.free-product');
        }

        $data = [
            'title' => 'Coba Tryout Gratis',
            'customer' => Customer::where('id', Auth::user()->customer_id)->first(),
        ];
        return view('main-web.produk.daftar-tryout-gratis', $data);
    }

    public function profil()
    {
        $data = [
            'title' => 'Profil Saya',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
        ];
        return view('main-web.profil.profil', $data);
    }

    public function kebijakanPrivasi()
    {
        $data = [
            'title' => 'Kebijakan Privasi',
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.kebijakan-privasi', $data);
    }

    public function termOfService()
    {
        $data = [
            'title' => 'Syarat & Ketentuan',
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.term-of-service', $data);
    }

    public function tentang()
    {
        $data = [
            'title' => 'Tentang Vistar Indonesia',
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.tentang', $data);
    }

    public function kontak()
    {
        $data = [
            'title' => 'Kontak',
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.kontak', $data);
    }

    public function sitemap()
    {
        $urls = [
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.8,
                'title' => 'HomePage',
                'loc' => route('mainweb.index'),
            ],
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.8,
                'title' => 'Produk ' . config('app.name'),
                'loc' => route('mainweb.product'),
            ],
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.6,
                'title' => 'Tentang',
                'loc' => route('mainweb.tentang'),
            ],
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.6,
                'title' => 'Hubungi ' . config('app.name'),
                'loc' => route('mainweb.kontak'),
            ],
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.5,
                'title' => 'Kebijakan Privasi' . config('app.name'),
                'loc' => route('mainweb.kebijakan-privasi'),
            ],
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.5,
                'title' => 'Masuk - ' . config('app.name'),
                'loc' => route('auth.signin'),
            ],
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.5,
                'title' => 'Daftar - ' . config('app.name'),
                'loc' => route('auth.signup'),
            ],
            [
                'changefreq' => 'yearly',
                'lastmod' => '2021-03-01',
                'priority' => 0.5,
                'title' => 'Reset Password - ' . config('app.name'),
                'loc' => route('auth.reset-password'),
            ],
        ];

        // Take Product Data
        $products = Cache::remember('products_sitemap_all', 7 * 24 * 60 * 60, function () {
            return \App\Models\ProdukTryout::select(
                'id',
                'nama_tryout',
                'updated_at'
            )
                ->orderBy('created_at', 'DESC')
                ->get();
        });
        foreach ($products as $item) {
            array_push($urls, [
                'changefreq' => 'monthly',
                'lastmod' => date('Y-m-d', strtotime($item->updated_at)),
                'priority' => 0.8,
                'title' => $item->nama_tryout . ' - Produk ' . config('app.name'),
                'loc' => route('mainweb.product-show', ['id' => $item->id]),
            ]);
        }

        return response()->view('main-web.home.sitemap', ['urls' => $urls])
            ->header(
                'Content-Type',
                'application/xml'
            );
    }

}
