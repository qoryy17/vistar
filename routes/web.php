<?php

use App\Http\Controllers\Panel\Main;
use App\Http\Controllers\Panel\Users;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\Site;
use App\Http\Controllers\Panel\Tryouts;
use App\Http\Controllers\Landing\Emails;
use App\Http\Controllers\Landing\Orders;
use App\Http\Controllers\Panel\Referral;
use App\Http\Middleware\Auth\ProdukAuth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Landing\Profils;
use App\Http\Controllers\Panel\Customers;
use App\Http\Controllers\Panel\Kategoris;
use App\Http\Controllers\Customer\Tryoutc;
use App\Http\Controllers\Panel\ListOrders;
use App\Http\Controllers\Panel\Pengaturan;
use App\Http\Controllers\Panel\Testimonis;
use App\Http\Middleware\Auth\PanelRouting;
use App\Http\Controllers\Panel\Klasifikasis;
use App\Http\Controllers\Landing\GoogleOauth;
use App\Http\Controllers\Landing\MainWebsite;
use App\Http\Controllers\Landing\Autentifikasi;
use App\Http\Middleware\Customer\LoggedCustomer;
use App\Http\Controllers\Payment\TransactionController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Routing untuk website utama
Route::get('/', function () {
    return Redirect::to('/');
});

// Routing untuk autentifikasi menggunakan Google OAuth
Route::controller(GoogleOauth::class)->group(function () {
    Route::get('/auth-google', 'redirectToGoogleProvider')->name('auth.google');
    Route::get('/callback', 'handleGoogleCallback')->name('auth.callback');
});

// Payment Routing
Route::controller(TransactionController::class)->group(function () {
    Route::post('/payment/{vendor}/notification/handler', 'notificationHandler')->name('payment.notification.handler');
    Route::get('/payment/finish', 'callbackFinish')->name('payment.finish');
    Route::get('/payment/unfinish', 'callbackUnFinish')->name('payment.unfinish');
    Route::get('/payment/error', 'callbackError')->name('payment.error');
});

// Routing untuk autentifikasi manual melalui form
Route::controller(Autentifikasi::class)->group(function () {
    Route::get('/signin', 'signIn')->name('auth.signin');
    Route::get('/signup', 'signUp')->name('auth.signup');
    Route::post('/register-user', 'registerUser')->name('auth.register');
    Route::get('/reset-password', 'resetPassword')->name('auth.reset-password');
    Route::post('/send-email', 'sendLinkEmail')->name('auth.send-link-email');
    Route::get('/password/reset/{token}', 'formResetPassword')->name('auth.password-reset');
    Route::post('/simpan-reset-password', 'simpanPasswordReset')->name('auth.simpanPasswordReset');
    Route::post('/auth-signin', 'authSignIn')->name('auth.signin-proses');
    Route::post('/auth-sign-out', 'authSignOut')->name('auth.signout-proses');
});

Route::controller(Emails::class)->group(function () {
    Route::get('/verifikasi-email/{id}/{hash}', 'verify')->name('verification.verify');
});

// Routing untuk menu utama landing website Vi Star Indonesia
Route::controller(MainWebsite::class, VerifyCsrfToken::class)->group(function () {
    Route::get('/', 'index')->name('mainweb.index');
    Route::get('/profil-saya', 'profil')->name('mainweb.profil-saya')->middleware(PanelRouting::class);
    Route::get('/produk-berbayar', 'produkBerbayar')->name('mainweb.produk-berbayar');
    Route::get('/produk-gratis', 'produkGratis')->name('mainweb.produk-gratis');
    Route::get('/search-tryout-berbayar/search', 'searchProdukBerbayar')->name('mainweb.search-produk-berbayar');
    Route::get('/search-tryout-gratis/search', 'searchProdukGratis')->name('mainweb.search-produk-gratis')->middleware(PanelRouting::class);
    Route::post('/pesan-tryout-berbayar', 'pesanTryoutBerbayar')->name('mainweb.pesan-tryout-berbayar')->middleware(PanelRouting::class);
    Route::get('/keranjang-pesanan', 'keranjangPesanan')->name('mainweb.keranjang')->middleware(PanelRouting::class);
    Route::delete('/hapus-item-pesanan', 'hapusItemPesanan')->name('mainweb.hapus-item')->middleware(PanelRouting::class);

    Route::get('/daftar-tryout-gratis', 'daftarTryoutGratis')->name('mainweb.daftar-tryout-gratis')->middleware(ProdukAuth::class);
    Route::get('/kebijakan-privasi', 'kebijakanPrivasi')->name('mainweb.kebijakan-privasi');
    Route::get('/tentang', 'tentang')->name('mainweb.tentang');
    Route::get('/kontak', 'kontak')->name('mainweb.kontak');
});

// Routing untuk untuk order produk tryout dan pembayaran order tryout
Route::middleware(LoggedCustomer::class)->group(function () {
    Route::controller(Orders::class)->group(function () {
        Route::get('/order-tryout/{params}', 'orderTryout')->name('orders.detail-pesanan');
        Route::post('/pay-order-tryout', 'payOrder')->name('orders.pay-order');
        Route::post('/simpan-daftar-tryout', 'daftarGratis')->name('orders.simpan-gratis');
        Route::post('/pesan-tryout-gratis', 'pesanTryoutGratis')->name('mainweb.pesan-tryout-gratis');

        Route::post('/check-referral', 'checkReferral')->name('orders.check-referral');
    });
});

// Routing untuk melengkapi informasi profil user customer
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Profils::class)->group(function () {
        Route::post('/update-foto', 'ubahFoto')->name('profils.ubah-foto');
        Route::post('/update-profil-pengguna', 'ubahProfil')->name('profils.ubah-profil');
        Route::post('/update-password', 'ubahPassword')->name('profils.ubah-password');
    });
});


// Routing untuk main panel kendali
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Main::class)->group(function () {
        Route::get('/main/dashboard', 'index')->name('main.beranda');
        Route::get('/main/chart', 'getChart')->name('main.chart');
        Route::get('/profil-admin', 'profilPengguna')->name('main.profil-admin');
        Route::get('/pengaturan', 'pengaturan')->name('main.pengaturan');
        Route::get('/banner', 'banner')->name('main.banner');
        Route::get('/faq', 'faq')->name('main.faq');
        Route::get('/logs', 'logs')->name('main.logs');
        Route::get('/versi', 'versi')->name('main.versi');
    });
});

// Routing untuk main panel manajemen pengaturan aplikasi dan web
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Pengaturan::class)->group(function () {
        Route::get('/form-banner/{param}/{id}', 'formBanner')->name('pengaturan.form-banner');
        Route::get('/form-faq/{param}/{id}', 'formFaq')->name('pengaturan.form-faq');
        Route::post('/simpan-pengaturan', 'simpanPengaturanWeb')->name('pengaturan.simpan-web');
        Route::delete('/hapus-logs', 'hapusLogs')->name('pengaturan.hapus-logs');
        Route::post('/update-profil', 'updateProfil')->name('pengaturan.update-profil');
    });
});

// Routing untuk main panel manajemen user/customer
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Customers::class)->group(function () {
        Route::get('/user-customer', 'index')->name('customer.main');
        Route::post('/blokir-customer', 'blokirCustomer')->name('customer.blokir');
        Route::delete('/hapus-customer', 'hapusCustomer')->name('customer.hapus');
    });
});

// Routing untuk main panel manajemen users/superadmin/admin/finance
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Users::class)->group(function () {
        Route::get('/user-all', 'index')->name('user.main');
        Route::get('/form-user/{param}/{id}', 'formUser')->name('user.form-user');
        Route::post('/simpan-user', 'simpanUser')->name('user.simpan');
        Route::post('/blokir-user', 'blokirUser')->name('user.blokir');
        Route::post('/ubah-password', 'ubahPassword')->name('user.ubah-password');
        Route::delete('/hapus-user', 'hapusUsers')->name('user.hapus');
    });
});

// Routing untuk main panel tryout
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Tryouts::class)->group(function () {
        Route::get('/produk-tryout', 'index')->name('tryouts.index');
        Route::get('/detail-produk/{id}', 'detailProduk')->name('tryouts.detail-produk');
        Route::get('/form-produk-tryout/{param}/{id}', 'formProdukTryout')->name('tryouts.form');

        Route::post('/simpan-produk-tryout', 'simpanProdukTryout')->name('tryouts.simpan');
        Route::delete('/hapus-produk-tryout', 'hapusProdukTryout')->name('tryouts.hapus');
        Route::post('/duplikat-produk-tryout', 'duplikatProdukTryout')->name('tryouts.duplikat');

        Route::get('/soal-tryout/{id}', 'soalTryout')->name('tryouts.soal');
        Route::get('/form-soal-tryout/{param}/{id}/{soal}', 'formSoalTryout')->name('tryouts.form-soal');
        Route::post('/simpan-soal-tryout', 'simpanSoalUjian')->name('tryouts.simpan-soal');
        Route::delete('/hapus-soal-tryout', 'hapusSoalUjian')->name('tryouts.hapus-soal');

        Route::get('/data-peserta-tryout', 'pesertaTryout')->name('tryouts.peserta-tryout');
        Route::get('/pengajuan-tryout-gratis', 'tryoutGratis')->name('tryouts.pengajuan-tryout-gratis');
        Route::post('/validasi-pengajuan-tryout-gratis', 'validasiTryoutGratis')->name('tryouts.validasi-pengajuan-tryout-gratis');
        // route::get('/see-email', 'email');
    });
});

Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(ListOrders::class)->group(function () {
        Route::get('/order-tryout', 'index')->name('listOrders.main');
        Route::get('/detil-order/{orderID}', 'detilOrder')->name('listOrders.detil');

        Route::get('/export-order-pdf', 'exportOrderToPDF')->name('listOrders.export-to-pdf');
        Route::get('/export-order-excel', 'exportOrderToExcel')->name('listOrders.export-to-excel');
    });
});

// Routing untuk main panel kategori
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Kategoris::class)->group(function () {
        Route::get('/kategori-produk-tryout/{produk}', 'index')->name('kategori.index');
        Route::get('/form-kategori-produk/{param}/{id}', 'formKategori')->name('kategori.form-kategori');

        Route::post('/simpan-kategori-produk', 'simpanKategori')->name('kategori.simpan');
        Route::post('/aktif-kategori', 'ubahAktifKategori')->name('kategori.aktif');
        Route::delete('/hapus-kategori-produk', 'hapusKategori')->name('kategori.hapus');
    });
});

Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Klasifikasis::class)->group(function () {
        Route::get('/klasifikasi-soal', 'index')->name('klasifikasi.index');
        Route::get('/form-klasifikasi-soal/{param}/{id}', 'formKlasifikasiSoal')->name('klasifikasi.form-klasifikasi');

        Route::post('/simpan-klasifikasi-soal', 'simpanKlasifikasiSoal')->name('klasifikasi.simpan');
        Route::post('/aktif-klasifikasi-soal', 'ubahAktifKlasifikasi')->name('klasifikasi.aktif');
        Route::delete('/hapus-klasifikasi-soal', 'hapusKlasifikasi')->name('klasifikasi.hapus');
    });
});

// Routing untuk main panel testimoni
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Referral::class)->group(function () {
        Route::get('/referral', 'index')->name('referral.main');
        Route::get('/referral/{kodeReferral}/{namaLengkap}', 'detailReferral')->name('referral.detil');
    });
});

// Routing untuk main panel testimoni
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Testimonis::class)->group(function () {
        Route::get('/testimoni', 'index')->name('testimoni.main');
        Route::get('publish-testimoni/{id}/{publish}', 'publishTestimoni')->name('testimoni.publish');
        Route::delete('/hapus-testimoni', 'hapusTestimoni')->name('testimoni.hapus');
    });
});

// Route customer main panel
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Site::class)->group(function () {
        Route::get('/site/dashboard', 'index')->name('site.main');
        Route::get('/tryout-berbayar', 'tryoutBerbayar')->name('site.tryout-berbayar')->middleware(ProdukAuth::class);
        Route::get('/tryout-gratis', 'tryoutGratis')->name('site.tryout-gratis');
        Route::get('/event-tryout', 'eventTryout')->name('site.event-tryout');

        Route::get('/pembelian-produk', 'pembelian')->name('site.pembelian');
        Route::get('/pembelian/search', 'searchPembelian')->name('site.search-pembelian');
    });
});

// Route customer untuk ujian
Route::middleware(PanelRouting::class)->group(function () {
    Route::controller(Tryoutc::class)->group(function () {
        Route::post('/ujian-tryout', 'berandaUjian')->name('ujian.main');
        Route::get('/progress-ujian/{id}/{param}', 'progressUjian')->name('ujian.progress');
        Route::post('/simpan-jawaban-ujian', 'simpanJawaban')->name('ujian.simpan-jawaban');
        Route::get('/simpan-hasil-ujian-tryout/{id}/{kode_soal}/{param}', 'simpanHasilUjian')->name('ujian.simpan-hasil');
        Route::get('/hasil-ujian/{id}/{ujianID}/{produkID}', 'hasilUjian')->name('ujian.hasil');

        Route::get('/testimoni-ujian', 'testimoniUjian')->name('ujian.testimoni');
        Route::post('/simpan-testimoni', 'simpanTestimoni')->name('ujian.simpan-testimoni');
    });
});
