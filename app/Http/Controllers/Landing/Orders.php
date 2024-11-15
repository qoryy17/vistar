<?php

namespace App\Http\Controllers\Landing;

use Exception;
use App\Models\User;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KeranjangOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Services\Payment\MidtransService;
use App\Http\Controllers\PromoCodeController;
use App\Models\Sertikom\KeranjangOrderSertikom;
use App\Http\Requests\Customer\TryoutGratisRequest;
use App\Models\Sertikom\OrderPelatihanSeminarModel;

class Orders extends Controller
{
    public function orderTryout(Request $request)
    {
        $param = $request->params;
        try {
            $param = Crypt::decrypt($param);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pesanan Tidak ditemukan!');
        }

        $orders = DB::table('keranjang_order')->select('keranjang_order.*', 'produk_tryout.id as idProduk', 'produk_tryout.nama_tryout', 'produk_tryout.keterangan', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status')
            ->leftJoin('produk_tryout', 'keranjang_order.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('keranjang_order.customer_id', '=', Auth::user()->customer_id)
            ->where('keranjang_order.id', '=', $param)
            ->whereNot('kategori_produk.status', 'Gratis')->orderBy('keranjang_order.updated_at', 'DESC')
            ->get();

        if ($orders->count() <= 0) {
            return redirect()->route('site.pembelian');
        }

        $data = [
            'title' => 'Pembayaran Pesanan',
            'orders' => $orders,
        ];

        return view('main-web.produk.order-tryout', $data);
    }

    public function payOrder(Request $request)
    {
        $cartId = Crypt::decrypt($request->id);

        // Check Promo Code
        $promoCode = $request->promo_code;
        $promoCodeData = null;
        if ($promoCode) {
            $checkPromoCode = PromoCodeController::checkPromoCode($promoCode);
            if ($checkPromoCode['result'] !== 'success') {
                return response()->json($checkPromoCode, 200);
            }

            $promoCodeData = $checkPromoCode['data'];
        }

        // Cari data customer
        $customer = Customer::findOrFail(Auth::user()->customer_id);

        // Cari data produk tryout
        $tryout = DB::table('keranjang_order')->select('keranjang_order.*', 'produk_tryout.id as idProduk', 'produk_tryout.nama_tryout', 'produk_tryout.keterangan', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status')
            ->leftJoin('produk_tryout', 'keranjang_order.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('keranjang_order.customer_id', '=', Auth::user()->customer_id)
            ->where('keranjang_order.id', '=', $cartId)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->first();
        if (!$tryout) {
            return response()->json([
                'result' => 'error',
                'title' => "Keranjang tidak ditemukan, Silahkan muat ulang halaman.",
            ]);
        }

        $referensiOrderID = $tryout->idProduk;
        $orderID = Str::uuid();

        $subTotal = $tryout->harga;
        $total = $subTotal;
        // Check if there is promo price
        if ($tryout->harga_promo !== 0 && $tryout->harga_promo !== null) {
            $subTotal = $tryout->harga_promo;
            $total = $subTotal;
        }
        $subTotal = intval($subTotal);
        $total = intval($total);

        $discount = 0;
        if ($promoCodeData) {
            if ($promoCodeData['promo']['type'] === 'percent') {
                $discount = $subTotal * $promoCodeData['promo']['value'] / 100;
            } elseif ($promoCodeData['promo']['type'] === 'deduction') {
                $discount = $promoCodeData['promo']['value'];
            }

            $total = $subTotal - $discount;
        }

        $purchaseItems = [
            [
                'item_id' => $tryout->idProduk,
                'item_name' => $tryout->nama_tryout,
                'discount' => $discount,
                'price' => $total,
                'quantity' => 1,
            ],
        ];

        $midtransService = new MidtransService();
        $snapToken = $midtransService->createOrder([
            'orderID' => $orderID,
            'grossAmount' => $total,
            'customerFullName' => $customer->nama_lengkap,
            'customerEmail' => Auth::user()->email,
            'customerPhone' => $customer->kontak,
            'customerBillingAddress' => $customer->alamat,
            'itemDetails' => [
                [
                    'id' => $referensiOrderID,
                    'price' => intval($total),
                    'quantity' => 1,
                    'name' => 'Pembayaran : ' . $tryout->nama_tryout,
                ],
            ],
        ]);

        try {
            DB::beginTransaction();

            $status = 'pending';

            // Add Order Data
            $buatOrder = new OrderTryout();
            $buatOrder->id = $orderID;
            $buatOrder->faktur_id = 'F' . rand(1, 999);
            $buatOrder->customer_id = Auth::user()->customer_id;
            $buatOrder->nama = $customer->nama_lengkap;
            $buatOrder->produk_tryout_id = $referensiOrderID;
            $buatOrder->status_order = $status;

            if (!$buatOrder->save()) {
                throw new Exception('Order gagal disimpan');
            }

            // Add Payment Data
            $savePayment = Payment::create([
                'id' => Str::uuid(),
                'customer_id' => Auth::user()->customer_id,
                'ref_order_id' => $orderID,
                'snap_token' => $snapToken,
                'transaksi_id' => null,
                'subtotal' => $subTotal,
                'promo_type' => $promoCodeData ? $promoCodeData['type'] : null,
                'promo_code' => $promoCodeData ? $promoCodeData['code'] : null,
                'promo_data' => $promoCodeData ? json_encode($promoCodeData) : null,
                'discount' => $discount,
                'nominal' => $total,
                'status_transaksi' => $status,
            ]);
            if (!$savePayment) {
                throw new Exception('Pembayaran gagal disimpan');
            }

            // Hapus Keranjang
            KeranjangOrder::where('id', $cartId)->delete();

            // Delete Activated Promotion Code
            PromoCodeController::deleteCookie();

            DB::commit();

            return response()->json([
                'result' => 'success',
                'title' => 'Order berhasil dibuat',
                'data' => [
                    'transaction_id' => $orderID,
                    'total_price' => $total,
                    'total_tax' => 0,
                    'total_shipping' => 0,
                    'currency' => 'IDR',
                    'coupon' => $promoCode,
                    'purchase_items' => $purchaseItems,
                    'user_data' => [
                        'id' => Auth::id(),
                        'full_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                    'snap_token' => $snapToken,
                ],
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(['result' => 'error', 'title' => $th->getMessage()], 500);
        }
    }

    public function daftarGratis(TryoutGratisRequest $request): RedirectResponse
    {
        // Cek apakah sudah pernah coba gratis
        $cekGratisan = LimitTryout::where('customer_id', Auth::user()->customer_id)->where('status_validasi', 'Disetujui')->first();
        if ($cekGratisan) {
            return redirect()->route('mainweb.daftar-tryout-gratis')->with('errorMessage', 'Pendaftaran tryout gratis hanya boleh 1 kali !');
        }

        // check if there waiting request
        $checkWaitingStatus = LimitTryout::where('customer_id', Auth::user()->customer_id)->where('status_validasi', 'Menunggu')->first();
        if ($checkWaitingStatus) {
            return redirect()->route('mainweb.daftar-tryout-gratis')->with('errorMessage', 'Pendaftaran tryout gratis anda sedang diproses, silahkan tunggu informasi selanjutnya!');
        }

        $uploadedFiles = [];
        try {
            DB::beginTransaction();

            $uploadedDir = 'images/share-follow/';
            if (!Storage::disk('public')->exists($uploadedDir)) {
                Storage::disk('public')->makeDirectory($uploadedDir);
            }

            // Bukti share produk
            $fileBuktiShare = $request->file('buktiShare');

            $uploadedFileNameBuktiShare = time() . '-' . $fileBuktiShare->hashName();
            $uploadedPathBuktiShare = $uploadedDir . $uploadedFileNameBuktiShare;

            $fileUploadBuktiShare = $fileBuktiShare->storeAs($uploadedDir, $uploadedFileNameBuktiShare, 'public');
            if (!$fileUploadBuktiShare) {
                throw new Exception('Unggah bukti share gagal !');
            }
            array_push($uploadedFiles, $uploadedPathBuktiShare);

            // Bukti share follow
            $fileBuktiFollow = $request->file('buktiFollow');

            $uploadedFileNameBuktiFollow = time() . '-' . $fileBuktiFollow->hashName();
            $uploadedPathBuktiFollow = $uploadedDir . $uploadedFileNameBuktiFollow;

            $fileUploadBuktiFollow = $fileBuktiFollow->storeAs($uploadedDir, $uploadedFileNameBuktiFollow, 'public');
            if (!$fileUploadBuktiFollow) {
                throw new Exception('Unggah bukti follow gagal !');
            }
            array_push($uploadedFiles, $uploadedPathBuktiFollow);

            $limitTryout = LimitTryout::create([
                'customer_id' => Auth::user()->customer_id,
                'bukti_share' => $uploadedPathBuktiShare,
                'bukti_follow' => $uploadedPathBuktiFollow,
                'informasi' => htmlspecialchars($request->input('informasi')),
                'alasan' => htmlspecialchars($request->input('alasan')),
                'status_validasi' => 'Menunggu',
            ]);

            if (!$limitTryout) {
                return redirect()->back()->with('errorMessage', 'Pendaftaran gagal, silahkan coba lagi !')->withInput();
            }

            DB::commit();

            return redirect()->route('mainweb.daftar-tryout-gratis')->with('successMessage', 'Pendaftaran berhasil silahkan cek email secara berkala untuk informasi persetujuan dari kami. Maksimal verifikasi 1x24 jam oleh Admin !');
        } catch (\Throwable $th) {
            DB::rollback();

            // delete uploaded files
            foreach ($uploadedFiles as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            return redirect()->back()->with('errorMessage', $th->getMessage())->withInput();
        }
    }

    public function pesanTryoutGratis(Request $request)
    {
        // Cek apakah sudah pernah coba gratis
        $tryoutGratis = LimitTryout::where('customer_id', Auth::user()->customer_id)
            ->whereNull('produk_tryout_id')
            ->where('status_validasi', 'Disetujui')
            ->orderBy('created_at', 'DESC')
            ->first();
        if (!$tryoutGratis) {
            return redirect()->back()->with('errorMessage', 'Tidak ada pengajuan Tryout Gratis yang disetujui !');
        }
        if ($tryoutGratis->produk_tryout_id !== null) {
            return redirect()->route('site.main');
        }

        $tryoutGratis->produk_tryout_id = Crypt::decrypt($request->idProdukTryout);
        $save = $tryoutGratis->save();

        if ($save) {
            return redirect()->back()->with('errorMessage', 'Produk tryout gratis gagal dipilih !')->withInput();
        }

        return redirect()->route('site.tryout-gratis');
    }

    public function checkReferral(Request $request)
    {
        $request->validate(
            [
                'kodeReferral' => ['string', 'max:255'],
            ],
            [
                'kodeReferral.string' => 'Kode Referral harus berupa kalimat !',
                'kodeReferral.max' => 'Kode Referral maksimal 255 karakter',
            ]
        );

        $referralCodeInput = $request->input('referral_code');

        $kodeReferral = User::where('kode_referral', $referralCodeInput)->first();
        if ($kodeReferral) {
            // Jika valid, kirim respons sukses dengan data tambahan jika diperlukan
            return response()->json([
                'status' => 'success',
                'message' => 'Kode referral valid !',
            ]);
        } else {
            // Jika tidak valid, kirim respons error
            return response()->json([
                'status' => 'error',
                'message' => 'Kode referral tidak valid',
            ], 400);
        }
    }


    // Add Sertikom : Pelatihan, Seminar/Workshop
    public function orderSertikom(Request $request)
    {
        $param = $request->params;
        if ($request->category == 'pelatihan') {
            $viewPage = 'main-web.sertikom.payment-order-training';
        } elseif ($request->category == 'seminar') {
            $viewPage = 'main-web.sertikom.payment-order-seminar';
        } elseif ($request->category == 'workshop') {
            $viewPage = 'main-web.sertikom.payment-order-workshop';
        } else {
            return redirect()->back()->with('errorMessage', 'Kategori tidak valid !');
        }
        $categorySertikom = ucfirst($request->category);
        try {
            $param = Crypt::decrypt($param);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pesanan Tidak ditemukan!');
        }

        $orders = DB::table('keranjang_order_sertikom')->select(
            'keranjang_order_sertikom.*',
            'produk_pelatihan_seminar.id as idProduk',
            'produk_pelatihan_seminar.produk',
            'produk_pelatihan_seminar.harga',
            'produk_pelatihan_seminar.deskripsi',
            'produk_pelatihan_seminar.tanggal_mulai',
            'produk_pelatihan_seminar.tanggal_selesai',
            'topik_keahlian.topik',
            'kategori_produk.judul',
            'kategori_produk.status'
        )
            ->leftJoin('produk_pelatihan_seminar', 'keranjang_order_sertikom.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('keranjang_order_sertikom.customer_id', '=', Auth::user()->customer_id)
            ->where('keranjang_order_sertikom.id', '=', $param)
            ->where('kategori_produk.judul', $categorySertikom)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->orderBy('keranjang_order_sertikom.updated_at', 'DESC')
            ->get();

        if ($orders->count() <= 0) {
            return redirect()->route('site.pembelian-sertikom', ['category' => $categorySertikom]);
        }

        $data = [
            'title' => 'Pembayaran Pesanan',
            'orders' => $orders,
        ];

        return view($viewPage, $data);
    }

    public function payOrderSertikom(Request $request)
    {
        $cartId = Crypt::decrypt($request->id);

        // Search customer by id
        $customer = Customer::findOrFail(Auth::user()->customer_id);

        // Checking category product sertikom
        if ($request->category == 'pelatihan') {
            $viewPage = 'main-web.sertikom.payment-order-training';
        } elseif ($request->category == 'seminar') {
            $viewPage = 'main-web.sertikom.payment-order-seminar';
        } elseif ($request->category == 'workshop') {
            $viewPage = 'main-web.sertikom.payment-order-workshop';
        } else {
            return redirect()->back()->with('errorMessage', 'Kategori tidak valid !');
        }
        $categorySertikom = ucfirst($request->category);

        // Search product training or seminar/workshop
        $sertikom = DB::table('keranjang_order_sertikom')->select(
            'keranjang_order_sertikom.*',
            'produk_pelatihan_seminar.id as idProduk',
            'produk_pelatihan_seminar.produk',
            'produk_pelatihan_seminar.harga',
            'produk_pelatihan_seminar.deskripsi',
            'produk_pelatihan_seminar.tanggal_mulai',
            'produk_pelatihan_seminar.tanggal_selesai',
            'topik_keahlian.topik',
            'kategori_produk.judul',
            'kategori_produk.status'
        )
            ->leftJoin('produk_pelatihan_seminar', 'keranjang_order_sertikom.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('keranjang_order_sertikom.customer_id', '=', Auth::user()->customer_id)
            ->where('keranjang_order_sertikom.id', '=', $cartId)
            ->where('kategori_produk.judul', $categorySertikom)
            ->whereNot('kategori_produk.status', 'Gratis')
            ->first();
        if (!$sertikom) {
            return response()->json([
                'result' => 'error',
                'title' => "Keranjang tidak ditemukan, Silahkan muat ulang halaman.",
            ]);
        }

        $referensiOrderID = $sertikom->idProduk;
        $orderID = Str::uuid();

        $subTotal = $sertikom->harga;
        $total = $subTotal;


        $purchaseItems = [
            [
                'item_id' => $sertikom->idProduk,
                'item_name' => $sertikom->produk,
                'discount' => 0,
                'price' => $total,
                'quantity' => 1,
            ],
        ];

        $midtransService = new MidtransService();
        $snapToken = $midtransService->createOrder([
            'orderID' => $orderID,
            'grossAmount' => $total,
            'customerFullName' => $customer->nama_lengkap,
            'customerEmail' => Auth::user()->email,
            'customerPhone' => $customer->kontak,
            'customerBillingAddress' => $customer->alamat,
            'itemDetails' => [
                [
                    'id' => $referensiOrderID,
                    'price' => intval($total),
                    'quantity' => 1,
                    'name' => 'Pembayaran : ' . $sertikom->produk,
                ],
            ],
        ]);

        try {
            DB::beginTransaction();

            $status = 'pending';

            // Add Order Data
            $buatOrder = new OrderPelatihanSeminarModel();
            $buatOrder->id = $orderID;
            $buatOrder->faktur_id = 'F' . rand(1, 999);
            $buatOrder->customer_id = Auth::user()->customer_id;
            $buatOrder->nama = $customer->nama_lengkap;
            $buatOrder->produk_pelatihan_seminar_id = $referensiOrderID;
            $buatOrder->status_order = $status;

            if (!$buatOrder->save()) {
                throw new Exception('Order gagal disimpan');
            }

            // Add Payment Data
            $savePayment = Payment::create([
                'id' => Str::uuid(),
                'customer_id' => Auth::user()->customer_id,
                'ref_order_id' => $orderID,
                'snap_token' => $snapToken,
                'transaksi_id' => null,
                'subtotal' => $subTotal,
                'promo_type' => null,
                'promo_code' => null,
                'promo_data' => null,
                'discount' => 0,
                'nominal' => $total,
                'status_transaksi' => $status,
            ]);
            if (!$savePayment) {
                throw new Exception('Pembayaran gagal disimpan');
            }

            // Remove item on cart
            KeranjangOrderSertikom::where('id', $cartId)->delete();

            DB::commit();

            return response()->json([
                'result' => 'success',
                'title' => 'Order berhasil dibuat',
                'data' => [
                    'transaction_id' => $orderID,
                    'total_price' => $total,
                    'total_tax' => 0,
                    'total_shipping' => 0,
                    'currency' => 'IDR',
                    'coupon' => null,
                    'purchase_items' => $purchaseItems,
                    'user_data' => [
                        'id' => Auth::id(),
                        'full_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                    'snap_token' => $snapToken,
                ],
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(['result' => 'error', 'title' => $th->getMessage()], 500);
        }
    }
}
