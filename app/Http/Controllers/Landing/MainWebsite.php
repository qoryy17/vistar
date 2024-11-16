<?php

namespace App\Http\Controllers\Landing;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Helpers\BerandaUI;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use App\Models\ProdukTryout;
use Illuminate\Http\Request;
use App\Helpers\RecordVisitor;
use App\Models\KategoriProduk;
use App\Models\KeranjangOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\Models\Sertikom\TopikKeahlianModel;
use App\Models\Sertikom\PesertaSertikomModel;
use App\Models\Sertikom\KeranjangOrderSertikom;
use App\Models\Sertikom\SertifikatSertikomModel;
use App\Models\Sertikom\OrderPelatihanSeminarModel;
use App\Models\Sertikom\ProdukPelatihanSeminarModel;

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
            'productTraining' => $this->getProductSertikom(\App\Enums\FeatureEnum::TRAINING),
            'productSeminar' => $this->getProductSertikom(\App\Enums\FeatureEnum::SEMINAR),
            'productWorkshop' => $this->getProductSertikom(\App\Enums\FeatureEnum::WORKSHOP),
        ];

        return view('main-web.home.beranda', $data);
    }

    protected function getProductSertikom(\App\Enums\FeatureEnum $feature)
    {
        if ($feature->value == 'pelatihan') {
            $sertikomCategory = 'Pelatihan';
        } elseif ($feature->value == 'seminar') {
            $sertikomCategory = 'Seminar';
        } elseif ($feature->value == 'workshop') {
            $sertikomCategory = 'Workshop';
        } else {
            return redirect()->route('mainweb.index')->with('error', 'Kategori tidak valid !');
        }

        return DB::table('produk_pelatihan_seminar')
            ->select(
                'produk_pelatihan_seminar.*',
                'instruktur.instruktur',
                'instruktur.keahlian',
                'instruktur.deskripsi as instruktur_deskripsi',
                'topik_keahlian.topik',
                'kategori_produk.judul',
                'kategori_produk.status as produk_status'
            )->leftJoin('instruktur', 'produk_pelatihan_seminar.instruktur_id', '=', 'instruktur.id')
            ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('produk_pelatihan_seminar.publish', 'Y')
            ->where('produk_pelatihan_seminar.status', 'Tersedia')
            ->where('kategori_produk.judul', $sertikomCategory)
            ->where('kategori_produk.status', 'Berbayar')
            ->orderBy('produk_pelatihan_seminar.updated_at', 'DESC')->limit(3)->get();
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
        $title = 'Tryout Simulasi CAT ' . config('app.name');
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

    public function productShowDeprecated(int $id)
    {
        return $this->productShow(\App\Enums\FeatureEnum::TRYOUT, $id);
    }

    public function productShow(\App\Enums\FeatureEnum $feature, int $id)
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

    public function privacyPolicy()
    {
        $data = [
            'title' => 'Kebijakan Privasi - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.pages.privacy-policy', $data);
    }

    public function termOfService()
    {
        $data = [
            'title' => 'Syarat & Ketentuan - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.pages.term-of-service', $data);
    }

    public function aboutUs()
    {
        $data = [
            'title' => 'Tentang - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.pages.about-us', $data);
    }

    public function contactUs()
    {
        $data = [
            'title' => 'Kontak - ' . config('app.name'),
            'web' => BerandaUI::web(),
        ];
        return view('main-web.pages.contact-us', $data);
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
                'loc' => route('mainweb.about-us'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Hubungi ' . config('app.name'),
                'loc' => route('mainweb.contact-us'),
                'images' => [$defaultImage],
            ],
            [
                'changefreq' => 'monthly',
                'lastmod' => '2024-09-25',
                'priority' => 0.5,
                'title' => 'Kebijakan Privasi' . config('app.name'),
                'loc' => route('mainweb.privacy-policy'),
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
                'loc' => route('mainweb.product.tryout.show', ['feature' => \App\Enums\FeatureEnum::TRYOUT->value, 'id' => $item->id]),
                'images' => $images,
            ]);
        }

        // Product Sertikom (Pelatihan, Seminar, Workshop)
        $productsTraining = Cache::remember('products_sertikom_training_sitemap_all', 7 * 24 * 60 * 60, function () {
            return \App\Models\Sertikom\ProdukPelatihanSeminarModel::select(
                'produk_pelatihan_seminar.id',
                'produk_pelatihan_seminar.produk',
                'produk_pelatihan_seminar.thumbnail',
                'produk_pelatihan_seminar.updated_at',
                'kategori_produk.judul'
            )->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_pelatihan_seminar.status', 'Tersedia')
                ->where('kategori_produk.judul', 'Pelatihan')
                ->orderBy('produk_pelatihan_seminar.created_at', 'DESC')
                ->get();
        });
        $productsSeminar = Cache::remember('products_sertikom_seminar_sitemap_all', 7 * 24 * 60 * 60, function () {
            return \App\Models\Sertikom\ProdukPelatihanSeminarModel::select(
                'produk_pelatihan_seminar.id',
                'produk_pelatihan_seminar.produk',
                'produk_pelatihan_seminar.thumbnail',
                'produk_pelatihan_seminar.updated_at',
                'kategori_produk.judul'
            )->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_pelatihan_seminar.status', 'Tersedia')
                ->where('kategori_produk.judul', 'Seminar')
                ->orderBy('produk_pelatihan_seminar.created_at', 'DESC')
                ->get();
        });
        $productsWorkshop = Cache::remember('products_sertikom_workshop_sitemap_all', 7 * 24 * 60 * 60, function () {
            return \App\Models\Sertikom\ProdukPelatihanSeminarModel::select(
                'produk_pelatihan_seminar.id',
                'produk_pelatihan_seminar.produk',
                'produk_pelatihan_seminar.thumbnail',
                'produk_pelatihan_seminar.updated_at',
                'kategori_produk.judul'
            )->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_pelatihan_seminar.status', 'Tersedia')
                ->where('kategori_produk.judul', 'Workshop')
                ->orderBy('produk_pelatihan_seminar.created_at', 'DESC')
                ->get();
        });
        $lastModProductSertikom = '2024-11-15';
        foreach ($productsTraining as $itemTraining) {
            $itemUpdatedAtTraining = date('Y-m-d', strtotime($itemTraining->updated_at));
            if (strtotime($lastModProductSertikom) < strtotime($itemUpdatedAtTraining)) {
                $lastModProductSertikom = $itemUpdatedAtTraining;
            }
            $imagesTraining = [];
            $thumbnailTraining = 'storage/' . $itemTraining->thumbnail;
            if (is_file($thumbnailTraining)) {
                array_push($imagesTraining, asset($thumbnailTraining));
            } else {
                array_push($imagesTraining, $defaultImage);
            }
            array_push($urls, [
                'changefreq' => 'weekly',
                'lastmod' => $itemUpdatedAtTraining,
                'priority' => 0.8,
                'title' => $itemTraining->produk . ' - Produk ' . config('app.name'),
                'loc' => route('mainweb.product-sertikom.training-seminar.show', ['feature' => \App\Enums\FeatureEnum::TRAINING->value, 'id' => $itemTraining->id]),
                'images' => $imagesTraining,
            ]);
        }
        foreach ($productsSeminar as $itemSeminar) {
            $itemUpdatedAtSeminar = date('Y-m-d', strtotime($itemSeminar->updated_at));
            if (strtotime($lastModProductSertikom) < strtotime($itemUpdatedAtSeminar)) {
                $lastModProductSertikom = $itemUpdatedAtSeminar;
            }
            $imagesSeminar = [];
            $thumbnailSeminar = 'storage/' . $itemSeminar->thumbnail;
            if (is_file($thumbnailSeminar)) {
                array_push($imagesSeminar, asset($thumbnailSeminar));
            } else {
                array_push($imagesSeminar, $defaultImage);
            }
            array_push($urls, [
                'changefreq' => 'weekly',
                'lastmod' => $itemUpdatedAtSeminar,
                'priority' => 0.8,
                'title' => $itemSeminar->produk . ' - Produk ' . config('app.name'),
                'loc' => route('mainweb.product-sertikom.training-seminar.show', ['feature' => \App\Enums\FeatureEnum::SEMINAR->value, 'id' => $itemSeminar->id]),
                'images' => $imagesSeminar,
            ]);
        }
        foreach ($productsWorkshop as $itemWorkshop) {
            $itemUpdatedAtWorkshop = date('Y-m-d', strtotime($itemWorkshop->updated_at));
            if (strtotime($lastModProductSertikom) < strtotime($itemUpdatedAtWorkshop)) {
                $lastModProductSertikom = $itemUpdatedAtWorkshop;
            }
            $imagesWorkshop = [];
            $thumbnailWorkshop = 'storage/' . $itemWorkshop->thumbnail;
            if (is_file($thumbnailWorkshop)) {
                array_push($imagesWorkshop, asset($thumbnailWorkshop));
            } else {
                array_push($imagesWorkshop, $defaultImage);
            }
            array_push($urls, [
                'changefreq' => 'weekly',
                'lastmod' => $itemUpdatedAtWorkshop,
                'priority' => 0.8,
                'title' => $itemWorkshop->produk . ' - Produk ' . config('app.name'),
                'loc' => route('mainweb.product-sertikom.training-seminar.show', ['feature' => \App\Enums\FeatureEnum::WORKSHOP->value, 'id' => $itemWorkshop->id]),
                'images' => $imagesWorkshop,
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

    // Add : Pelatihan dan Seminar/Workshop
    protected function getExpertiseCategory(string $publish, null | string $expertiseId = null)
    {
        if (!$expertiseId) {
            return null;
        }

        return Cache::tags(['product_expertise_category_main_web:' . $expertiseId])->remember('product_expertise_category_main_web:' . $expertiseId . ',publish:' . $publish, 7 * 24 * 60 * 60, function () use ($expertiseId, $publish) {
            return DB::table('topik_keahlian')
                ->select(
                    'topik_keahlian.id',
                    'topik_keahlian.topik',
                    'topik_keahlian.deskripsi'
                )
                ->where('topik_keahlian.publish', $publish)
                ->where('topik_keahlian.id', $expertiseId)
                ->first();
        });
    }

    public function productSertikomShowDeprecated(string $category, int $id)
    {
        if ($category == 'pelatihan') {
            return $this->productSertikomShow(\App\Enums\FeatureEnum::TRAINING, $id);
        } elseif ($category == 'seminar') {
            return $this->productSertikomShow(\App\Enums\FeatureEnum::SEMINAR, $id);
        } elseif ($category == 'workshop') {
            return $this->productSertikomShow(\App\Enums\FeatureEnum::WORKSHOP, $id);
        } else {
            return redirect()->route('mainweb.index')->with('error', 'Kategori produk tidak ditemukan !');
        }
    }

    public function productSertikomShow(\App\Enums\FeatureEnum $feature, int $id)
    {
        if ($feature->value == 'pelatihan') {
            $cacheRemember = 'product_training_show_main_web:';
            $cacheTag = 'products_training_main_web';
            $cacheTagRecommendation = 'products_training_recomendation_main_web:';
            $cacheTagExcept = 'products_training_main_web:except:';
            $routingBack = 'mainweb.product-training';
            $errorMessageBack = 'Produk pelatihan tidak ditemukan';
            $viewPage = 'main-web.sertikom.product-training-show';
        } elseif ($feature->value == 'seminar') {
            $cacheRemember = 'product_seminar_show_main_web:';
            $cacheTag = 'products_seminar_main_web';
            $cacheTagRecommendation = 'products_seminar_recomendation_main_web:';
            $cacheTagExcept = 'products_seminar_main_web:except:';
            $routingBack = 'mainweb.product-seminar';
            $errorMessageBack = 'Produk seminar tidak ditemukan';
            $viewPage = 'main-web.sertikom.product-seminar-show';
        } elseif ($feature->value == 'workshop') {
            $cacheRemember = 'product_workshop_show_main_web:';
            $cacheTag = 'products_workshop_main_web';
            $cacheTagRecommendation = 'products_workshop_recomendation_main_web:';
            $cacheTagExcept = 'products_workshop_main_web:except:';
            $routingBack = 'mainweb.product-workshop';
            $errorMessageBack = 'Produk workshop tidak ditemukan';
            $viewPage = 'main-web.sertikom.product-workshop-show';
        } else {
            return redirect()->route('mainweb.index')->with('error', 'Kategori tidak valid !');
        }
        $product = Cache::remember($cacheRemember . $id, 7 * 24 * 60 * 60, function () use ($id) {
            $data = ProdukPelatihanSeminarModel::where('id', $id)
                ->select(
                    'id',
                    'produk',
                    'deskripsi',
                    'thumbnail',
                    'harga',
                    'tanggal_mulai',
                    'tanggal_selesai',
                    'instruktur_id',
                    'kategori_produk_id',
                    'topik_keahlian_id',
                )->with('instructor', function ($query) {
                    $query->select('id', 'instruktur', 'keahlian', 'deskripsi', 'publish');
                })
                ->with('category', function ($query) {
                    $query->select('id', 'judul', 'status');
                })
                ->with('expertise', function ($query) {
                    $query->select('id', 'topik', 'deskripsi', 'publish');
                });

            return $data->first();
        });

        if (!$product) {
            return redirect()->route($routingBack)->with(['errorMessage' => $errorMessageBack . ' silahkan pilih produk yang tersedia']);
        }

        $order = null;
        if (Auth::check()) {
            $order = OrderPelatihanSeminarModel::where('produk_pelatihan_seminar_id', $product->id)
                ->where('customer_id', Auth::user()->customer_id)
                ->where('status_order', 'paid')
                ->first();
        }

        $title = 'Produk ' . ucfirst($feature->value) . ' - ' . $product->produk;

        $categorySertikom = $feature->value;

        $productStatus = 'Berbayar';
        $recommendProducts = Cache::tags([$cacheTag, $cacheTagRecommendation . $id])->remember($cacheTagExcept . $id . ',status:' . $productStatus . ',kategori_sertikom:' . $categorySertikom, 7 * 24 * 60 * 60, function () use ($id, $productStatus, $categorySertikom) {
            $data = DB::table('produk_pelatihan_seminar')
                ->select(
                    'produk_pelatihan_seminar.*',
                    'instruktur.instruktur',
                    'instruktur.keahlian',
                    'instruktur.deskripsi as instruktur_deskripsi',
                    'topik_keahlian.topik',
                    'kategori_produk.judul',
                    'kategori_produk.status as produk_status'
                )->leftJoin('instruktur', 'produk_pelatihan_seminar.instruktur_id', '=', 'instruktur.id')
                ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
                ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_pelatihan_seminar.publish', 'Y')
                ->where('kategori_produk.judul', $categorySertikom)
                ->where('kategori_produk.status', $productStatus)
                ->where('produk_pelatihan_seminar.status', 'Tersedia')
                ->orderBy('produk_pelatihan_seminar.updated_at', 'DESC');

            return $data->limit(3)->get();
        });

        $data = [
            'title' => $title,
            'product' => $product,
            'order' => $order,
            'recommendProducts' => $recommendProducts,
        ];

        // Record record visitor on click prouct
        RecordVisitor::saveRecord([
            'ref_produk_id' => $product->id,
            'nama_produk' => $product->produk,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'tanggal' => date('Y-m-d')
        ]);
        return view($viewPage, $data);
    }

    public function productsSertikom(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $sertikomCategory = 'Pelatihan';
            $cacheTags = 'products_training_main_web';
            $cacheRemember = 'products_training_main_web:expertise_id:';
            $viewPage = 'main-web.sertikom.product-training';
            $descriptionPage = 'Temukan produk yang sempurna untuk Kamu! Dengan berbagai pilihan paket yang dirancang sesuai kebutuhan, ' . config('app.name') . ' Indonesia memberikan solusi terbaik untuk upgrade skill dan karir kamu';
        } elseif ($request->category == 'seminar') {
            $sertikomCategory = 'Seminar';
            $cacheTags = 'products_seminar_main_web';
            $cacheRemember = 'products_seminar_main_web:expertise_id:';
            $viewPage = 'main-web.sertikom.product-seminar';
            $descriptionPage = 'Temukan produk yang sempurna untuk Kamu! Dengan berbagai pilihan paket yang dirancang sesuai kebutuhan, ' . config('app.name') . ' memberikan solusi terbaik untuk update pengetahuan kamu dan perbanyak relasi dengan peserta seminar';
        } elseif ($request->category == 'workshop') {
            $sertikomCategory = 'Workshop';
            $cacheTags = 'products_workshop_main_web';
            $cacheRemember = 'products_workshop_main_web:expertise_id:';
            $viewPage = 'main-web.sertikom.product-workshop';
            $descriptionPage = 'Temukan produk yang sempurna untuk Kamu! Dengan berbagai pilihan paket yang dirancang sesuai kebutuhan, ' . config('app.name') . ' memberikan solusi terbaik untuk update pengetahuan kamu dan perbanyak relasi dengan peserta workshop';
        } else {
            return redirect()->route('mainweb.index')->with('error', 'Kategori produk tidak ditemukan !');
        }

        $title = ucfirst($request->category) . ' ' . config('app.name');

        $searchExpertiseId = $request->expertise_id ? htmlentities($request->expertise_id) : null;
        $searchName = $request->search_name ? htmlentities($request->search_name) : null;
        $page = intval(request()->get('page', 1));
        if (!is_numeric($page) || $page < 1) {
            $page = 1;
        }

        $expertisePublish = 'Y';
        $expertiseCategory = $this->getExpertiseCategory($expertisePublish, $searchExpertiseId);

        if ($expertiseCategory || $searchName) {
            $additionalTitle = [];
            if ($searchName) {
                array_push($additionalTitle, '`' . $searchName . '`');
            }
            if ($expertiseCategory) {
                array_push($additionalTitle, 'Topik ' . $sertikomCategory . ' `' . $expertiseCategory->topik . '`');
            }
            $title = 'Cari ' . implode(' ', $additionalTitle) . ' Produk ' . $sertikomCategory . ' ' . config('app.name');
        }

        $products = Cache::tags([$cacheTags])->remember($cacheRemember . $searchExpertiseId . ',sertikom:' . $sertikomCategory . ',publish:' . $expertisePublish . ',search:' . $searchName . ',page:' . $page, 7 * 24 * 60 * 60, function () use ($searchExpertiseId, $sertikomCategory, $expertisePublish, $searchName) {
            $data = DB::table('produk_pelatihan_seminar')
                ->select(
                    'produk_pelatihan_seminar.*',
                    'instruktur.instruktur',
                    'instruktur.keahlian',
                    'instruktur.deskripsi as instruktur_deskripsi',
                    'topik_keahlian.topik',
                    'kategori_produk.judul',
                    'kategori_produk.status as produk_status'
                )->leftJoin('instruktur', 'produk_pelatihan_seminar.instruktur_id', '=', 'instruktur.id')
                ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
                ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('produk_pelatihan_seminar.publish', 'Y')
                ->where('kategori_produk.judul', $sertikomCategory)
                ->where('kategori_produk.status', 'Berbayar')
                ->where('produk_pelatihan_seminar.status', 'Tersedia')
                ->orderBy('produk_pelatihan_seminar.updated_at', 'DESC');

            if ($searchExpertiseId) {
                $data = $data->where('topik_keahlian.id', '=', $searchExpertiseId);
            }
            if ($searchName) {
                $data = $data->whereLike('produk_pelatihan_seminar.produk', "%{$searchName}%");
            }

            return $data->paginate(9);
        });

        $expertisePublish = 'Y';
        $expertise = Cache::tags(['product_expertise_main_web'])->remember('product_expertise_main_web:publish' . $expertisePublish, 7 * 24 * 60 * 60, function () use ($expertisePublish) {
            return TopikKeahlianModel::where('publish', $expertisePublish)
                ->select('id', 'topik', 'deskripsi')
                ->get();
        });

        // Check Page
        if ($page > 1) {
            $title .= " - Halaman $page";
        }

        $data = [
            'title' => $title,
            'expertises' => $expertise,
            'searchExpertiseId' => $searchExpertiseId,
            'searchName' => $searchName,
            'products' => $products,
            'descriptionPage' => $descriptionPage
        ];

        return view($viewPage, $data);
    }

    public function addCartSertikom(Request $request): RedirectResponse
    {
        $userRole = Auth::user()->role;
        if ($userRole !== 'Customer') {
            return redirect()->back()->with('errorMessage', 'Jenis Akun Anda tidak dapat melakukan transaksi !');
        }

        try {
            $categorySertikom = $request->category;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }

        // Checking if product order same
        $sertikom = OrderPelatihanSeminarModel::where('produk_pelatihan_seminar_id', Crypt::decrypt($request->idProdukSertikom))->where('status_order', 'paid')->where('customer_id', Auth::user()->customer_id)->first();
        if ($sertikom) {
            return redirect()->route('mainweb.cart-sertikom', ['category' => $categorySertikom])->with('errorMessage', 'Tidak dapat memesan pelatihan yang sama sebelumnya !');
        }

        $cart = KeranjangOrderSertikom::where('produk_pelatihan_seminar_id', Crypt::decrypt($request->idProdukSertikom))->where('customer_id', Auth::user()->customer_id)->first();
        if ($cart) {
            return redirect()->route('mainweb.cart-sertikom', ['category' => $categorySertikom])->with('errorMessage', 'Tidak dapat menambahkan pelatihan yang sama pada keranjang pesanan !');
        }

        $createCart = [
            'produk_pelatihan_seminar_id' => Crypt::decrypt($request->idProdukSertikom),
            'customer_id' => Auth::user()->customer_id
        ];

        $saveCart = KeranjangOrderSertikom::create($createCart);

        if (!$saveCart) {
            return redirect()->back()->with('errorMessage', 'Pelatihan gagal ditambahkan dikeranjang !');
        }

        return redirect()->route('mainweb.cart-sertikom', ['category' => $categorySertikom]);
    }

    public function cartSertikom(Request $request)
    {

        if ($request->category == 'pelatihan') {
            $viewPage = 'main-web.sertikom.order-cart-training';
        } elseif ($request->category == 'seminar') {
            $viewPage = 'main-web.sertikom.order-cart-seminar';
        } elseif ($request->category == 'workshop') {
            $viewPage = 'main-web.sertikom.order-cart-workshop';
        } else {
            return redirect()->back()->with('errorMessage', 'Kategori tidak valid !');
        }

        $categorySertikom = ucfirst($request->category);

        $cartItems = DB::table('keranjang_order_sertikom')->select(
            'keranjang_order_sertikom.*',
            'produk_pelatihan_seminar.id as idProduk',
            'produk_pelatihan_seminar.produk',
            'produk_pelatihan_seminar.harga',
            'produk_pelatihan_seminar.deskripsi',
            'topik_keahlian.topik',
            'kategori_produk.judul',
            'kategori_produk.status'
        )
            ->leftJoin('produk_pelatihan_seminar', 'keranjang_order_sertikom.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('keranjang_order_sertikom.customer_id', '=', Auth::user()->customer_id)
            ->where('kategori_produk.judul', $categorySertikom)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->where('produk_pelatihan_seminar.status', 'Tersedia')
            ->orderBy('keranjang_order_sertikom.updated_at', 'DESC')
            ->get();

        $productIdsSelected = [];
        foreach ($cartItems as $item) {
            array_push($productIdsSelected, $item->idProduk);
        }

        $recommendProducts =  DB::table('produk_pelatihan_seminar')
            ->select(
                'produk_pelatihan_seminar.*',
                'instruktur.instruktur',
                'instruktur.keahlian',
                'instruktur.deskripsi as instruktur_deskripsi',
                'topik_keahlian.topik',
                'kategori_produk.judul',
                'kategori_produk.status as produk_status'
            )->leftJoin('instruktur', 'produk_pelatihan_seminar.instruktur_id', '=', 'instruktur.id')
            ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('produk_pelatihan_seminar.publish', 'Y')
            ->where('kategori_produk.judul', $categorySertikom)
            ->where('kategori_produk.status', 'Berbayar')
            ->where('produk_pelatihan_seminar.status', 'Tersedia')
            ->whereNotIn('produk_pelatihan_seminar.id', $productIdsSelected)
            ->orderBy('produk_pelatihan_seminar.updated_at', 'DESC')
            ->limit(3)->get();

        $data = [
            'title' => 'Keranjang Pesanan ' . $categorySertikom,
            'cartItems' => $cartItems,
            'recommendProducts' => $recommendProducts,
        ];

        return view($viewPage, $data);
    }

    public function deleteCartSertikom(Request $request): RedirectResponse
    {
        $itemSertikom = KeranjangOrderSertikom::findOrFail(Crypt::decrypt($request->id));
        if ($itemSertikom) {
            $itemSertikom->delete();
            return redirect()->route('mainweb.cart-sertikom', ['category' => $request->category])->with('successMessage', 'Item pesanan berhasil dihapus !');
        } else {
            return redirect()->route('mainweb.cart-sertikom', ['category' => $request->category])->with('errorMessage', 'Item pesanan gagal dihapus !');
        }
    }

    public function certificateSertikom(Request $request)
    {
        $participantCode = $request->kode_peserta ? $request->kode_peserta : null;
        $resultCertificate = null;
        $infoCertificate = null;

        if ($participantCode) {
            $searchParticipant = PesertaSertikomModel::where('kode_peserta', htmlentities($participantCode))->first();
            if ($searchParticipant) {
                $order = OrderPelatihanSeminarModel::find($searchParticipant->order_pelatihan_seminar_id);
                $product = ProdukPelatihanSeminarModel::find($order->produk_pelatihan_seminar_id);
                $certificate = SertifikatSertikomModel::where('peserta_sertikom_id', $searchParticipant->id)->first();

                $infoCertificate = [
                    'participant' => $searchParticipant,
                    'order' => $order,
                    'product' => $product,
                    'certificateNumber' => $certificate->nomor_sertifikat
                ];
                $resultCertificate = 'Found';
            } else {
                $resultCertificate = 'Not Found';
            }
        }

        $data = [
            'title' => 'Cek Sertifikat - ' . config('app.name'),
            'web' => BerandaUI::web(),
            'result' => $resultCertificate,
            'certificate' => $infoCertificate
        ];

        return view('main-web.sertikom.check-certificate', $data);
    }
}
