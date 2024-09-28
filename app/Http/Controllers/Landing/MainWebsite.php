<?php

namespace App\Http\Controllers\Landing;

use App\Enums\UserRole;
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
        $web = BerandaUI::web();

        // This is should from the database
        $productCategories = [
            [
                'id' => 5550,
                'title' => 'PPPK',
                'is_popular' => false,
                'features' => [
                    'Hasil Ujian',
                    'Grafik Hasil Ujian',
                    'Review Pembahasan Soal',
                ],
            ],
            [
                'id' => 19571,
                'title' => 'CPNS',
                'is_popular' => true,
                'features' => [
                    'Hasil Ujian',
                    'Grafik Hasil Ujian',
                    'Review Pembahasan Soal',
                ],
            ],
            [
                'id' => 86539,
                'title' => 'Kedinasan',
                'is_popular' => false,
                'features' => [
                    'Hasil Ujian',
                    'Grafik Hasil Ujian',
                    'Review Pembahasan Soal',
                ],
            ],
        ];

        $data = [
            'title' => $web->nama_bisnis . " " . $web->tagline,
            'web' => $web,
            'productCategories' => $productCategories,
        ];

        return view('main-web.home.beranda', $data);
    }

    protected function getProductCategory(string $status, null | string $categoryId = null)
    {
        if (!$categoryId) {
            return null;
        }

        return Cache::tags(['product_category_main_web:' . $categoryId])->remember('product_category_main_web:' . $categoryId . ',status:' . $status, 7 * 24 * 60 * 60, function () use ($categoryId, $status) {
            return DB::table('kategori_produk')
                ->select(
                    'kategori_produk.id',
                    'kategori_produk.judul'
                )
                ->where('kategori_produk.status', $status)
                ->where('kategori_produk.id', $categoryId)
                ->first();
        });
    }

    public function products(Request $request)
    {
        $title = 'Produk ' . config('app.name');
        $searchCategoryId = $request->category_id ? htmlentities($request->category_id) : null;
        $searchName = $request->search_name ? htmlentities($request->search_name) : null;
        $page = intval(request()->get('page', 1));
        if (!is_numeric($page) || $page < 1) {
            $page = 1;
        }

        $productStatus = 'Berbayar';

        $productCategory = $this->getProductCategory($productStatus, $searchCategoryId);

        if ($productCategory || $searchName) {
            $additionalTitle = [];
            if ($searchName) {
                array_push($additionalTitle, '`' . $searchName . '`');
            }
            if ($productCategory) {
                array_push($additionalTitle, 'Kategori `' . $productCategory->judul . '`');
            }
            $title = 'Cari ' . implode(' ', $additionalTitle) . ' Produk ' . config('app.name');
        }

        $products = Cache::tags(['products_main_web'])->remember('products_main_web:category_id:' . $searchCategoryId . ',status:' . $productStatus . ',search:' . $searchName . ',page:' . $page, 7 * 24 * 60 * 60, function () use ($searchCategoryId, $productStatus, $searchName) {
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

            return $data->paginate(9);
        });

        $categoryStatus = 'Berbayar';
        $categoryActive = 'Y';
        $categories = Cache::tags(['product_categories_main_web'])->remember('product_categories_main_web:status:' . $categoryStatus . ',active:' . $categoryActive, 7 * 24 * 60 * 60, function () use ($categoryStatus, $categoryActive) {
            return KategoriProduk::where('aktif', $categoryActive)
                ->select('id', 'judul')
                ->where('status', $categoryStatus)
                ->get();
        });

        // Check Page
        if ($page > 1) {
            $title .= " - Halaman $page";
        }

        $data = [
            'title' => $title,
            'categories' => $categories,
            'searchCategoryId' => $searchCategoryId,
            'searchName' => $searchName,
            'products' => $products,
        ];
        return view('main-web.produk.product', $data);
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
        $recommendProducts = Cache::tags(['products_main_web', 'products_recomendation_main_web:' . $id])->remember('products_main_web:except:' . $id . ',status:' . $productStatus, 7 * 24 * 60 * 60, function () use ($id, $productStatus) {
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
        return view('main-web.produk.product-show', $data);
    }

    public function freeProducts(Request $request)
    {
        $title = 'Produk Paket Tryout Gratis';
        $searchCategoryId = $request->category_id ? htmlentities($request->category_id) : null;
        $searchName = $request->search_name ? htmlentities($request->search_name) : null;
        $page = intval(request()->get('page', 1));
        if (!is_numeric($page) || $page < 1) {
            $page = 1;
        }

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

        $productStatus = 'Gratis';
        $productCategory = $this->getProductCategory($productStatus, $searchCategoryId);

        if ($productCategory || $searchName) {
            $additionalTitle = [];
            if ($searchName) {
                array_push($additionalTitle, '`' . $searchName . '`');
            }
            if ($productCategory) {
                array_push($additionalTitle, 'Kategori `' . $productCategory->judul . '`');
            }
            $title = 'Cari ' . implode(' ', $additionalTitle) . ' Produk Gratis ' . config('app.name');
        }

        $products = Cache::tags(['products_main_web'])->remember('products_main_web:category_id:' . $searchCategoryId . ',status:' . $productStatus . ',search:' . $searchName . ',page:' . $page, 7 * 24 * 60 * 60, function () use ($searchCategoryId, $productStatus, $searchName) {
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

            return $data->paginate(9);
        });

        $categoryStatus = 'Gratis';
        $categoryActive = 'Y';
        $categories = Cache::tags(['product_categories_main_web'])->remember('product_categories_main_web:status:' . $categoryStatus . ',active:' . $categoryActive, 7 * 24 * 60 * 60, function () use ($categoryStatus, $categoryActive) {
            return KategoriProduk::where('aktif', $categoryActive)
                ->select('id', 'judul')
                ->where('status', $categoryStatus)
                ->get();
        });

        // Check Page
        if ($page > 1) {
            $title .= " - Halaman $page";
        }

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
        $userRole = Auth::user()->role;
        if ($userRole !== 'Customer') {
            return redirect()->back()->with('errorMessage', 'Jenis Akun Anda tidak dapat melakukan transaksi !');
        }

        // Check apakah pernah memesan produk yang sama
        $tryout = OrderTryout::where('produk_tryout_id', Crypt::decrypt($request->idProdukTryout))->where('status_order', 'paid')->where('customer_id', Auth::user()->customer_id)->first();
        if ($tryout) {
            return Redirect::route('mainweb.keranjang')->with('errorMessage', 'Tidak dapat memesan produk yang sama sebelumnya !');
        }

        $keranjang = KeranjangOrder::where('produk_tryout_id', Crypt::decrypt($request->idProdukTryout))->where('customer_id', Auth::user()->customer_id)->first();
        if ($keranjang) {
            return Redirect::route('mainweb.keranjang')->with('errorMessage', 'Tidak dapat menambahkan produk yang sama pada keranjang pesanan !');
        }

        $keranjangOrder = new KeranjangOrder();
        $keranjangOrder->id = rand(1, 99) . rand(1, 999);
        $keranjangOrder->produk_tryout_id = Crypt::decrypt($request->idProdukTryout);
        $keranjangOrder->customer_id = Auth::user()->customer_id;
        $save = $keranjangOrder->save();

        if (!$save) {
            return redirect()->back()->with('errorMessage', 'Produk gagal ditambahkan dikeranjang !');
        }

        return redirect()->route('mainweb.keranjang');
    }

    public function keranjangPesanan()
    {

        $cartItems = DB::table('keranjang_order')->select('keranjang_order.*', 'produk_tryout.id as idProduk', 'produk_tryout.nama_tryout', 'produk_tryout.keterangan', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status')
            ->leftJoin('produk_tryout', 'keranjang_order.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('keranjang_order.customer_id', '=', Auth::user()->customer_id)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->orderBy('keranjang_order.updated_at', 'DESC')
            ->get();

        $productIdsSelected = [];
        foreach ($cartItems as $item) {
            array_push($productIdsSelected, $item->idProduk);
        }

        $recommendProducts = DB::table('produk_tryout')
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
            ->whereNot('kategori_produk.status', 'Gratis')
            ->whereNotIn('produk_tryout.id', $productIdsSelected)
            ->orderBy('produk_tryout.updated_at', 'DESC')
            ->limit(3)
            ->get();

        $data = [
            'title' => 'Keranjang Pesanan',
            'cartItems' => $cartItems,
            'recommendProducts' => $recommendProducts,
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

    public function profile()
    {
        $costumerData = null;
        if (Auth::user()->role === UserRole::CUSTOMER->value) {
            $costumerData = Customer::findOrFail(Auth::user()->customer_id);
        }
        $data = [
            'title' => 'Profil Saya - ' . config('app.name'),
            'customer' => $costumerData,
        ];

        return view('main-web.profile.profile', $data);
    }

    public function kebijakanPrivasi()
    {
        $data = [
            'title' => 'Kebijakan Privasi - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.kebijakan-privasi', $data);
    }

    public function termOfService()
    {
        $data = [
            'title' => 'Syarat & Ketentuan - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.term-of-service', $data);
    }

    public function tentang()
    {
        $data = [
            'title' => 'Tentang - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.tentang', $data);
    }

    public function kontak()
    {
        $data = [
            'title' => 'Kontak - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.tentang.kontak', $data);
    }

    public function sitemap()
    {
        $web = BerandaUI::web();

        $defaultImage = asset('resources/images/logo.png');
        $uploadedLogo = 'storage/' . $web->logo;
        if (is_file($uploadedLogo)) {
            $defaultImage = asset($uploadedLogo);
        }

        $urls = [
            [
                'changefreq' => 'weekly',
                'lastmod' => '2024-09-25',
                'priority' => 0.8,
                'title' => 'HomePage',
                'loc' => route('mainweb.index'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Tentang',
                'loc' => route('mainweb.tentang'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Hubungi ' . config('app.name'),
                'loc' => route('mainweb.kontak'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Kebijakan Privasi' . config('app.name'),
                'loc' => route('mainweb.kebijakan-privasi'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Masuk - ' . config('app.name'),
                'loc' => route('auth.signin'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Daftar - ' . config('app.name'),
                'loc' => route('auth.signup'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Reset Password - ' . config('app.name'),
                'loc' => route('auth.reset-password'),
                'images' => [$defaultImage],
            ],
        ];

        // Take Product Data
        $products = Cache::remember('products_sitemap_all', 7 * 24 * 60 * 60, function () {
            return \App\Models\ProdukTryout::select(
                'id',
                'nama_tryout',
                'thumbnail',
                'updated_at'
            )
                ->where('status', 'Tersedia')
                ->orderBy('created_at', 'DESC')
                ->get();
        });
        $lastModProduct = '2024-09-25';
        foreach ($products as $item) {
            $itemUpdatedAt = date('Y-m-d', strtotime($item->updated_at));
            if (strtotime($lastModProduct) < strtotime($itemUpdatedAt)) {
                $lastModProduct = $itemUpdatedAt;

            }
            $images = [];
            $thumbnail = 'storage/' . $item->thumbnail;
            if (is_file($thumbnail)) {
                array_push($images, asset($thumbnail));
            } else {
                array_push($images, $defaultImage);
            }
            array_push($urls, [
                'changefreq' => 'weekly',
                'lastmod' => $itemUpdatedAt,
                'priority' => 0.8,
                'title' => $item->nama_tryout . ' - Produk ' . config('app.name'),
                'loc' => route('mainweb.product-show', ['id' => $item->id]),
                'images' => $images,
            ]);
        }
        array_push($urls, [
            'changefreq' => 'weekly',
            'lastmod' => date('Y-m-d', strtotime($lastModProduct)),
            'priority' => 0.8,
            'title' => 'Produk ' . config('app.name'),
            'loc' => route('mainweb.product'),
            'images' => [$defaultImage],
        ]);

        return response()->view('main-web.home.sitemap', ['urls' => $urls])
            ->header(
                'Content-Type',
                'application/xml'
            );
    }
}
