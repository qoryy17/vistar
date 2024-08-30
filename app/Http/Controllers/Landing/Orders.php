<?php

namespace App\Http\Controllers\Landing;

use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Payment;
use App\Models\Customer;
use App\Mail\EmailFaktur;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KeranjangOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Customer\TryoutGratisRequest;
use App\Models\ReferralCustomer;

class Orders extends Controller
{
    public function orderTryout(Request $request)
    {
        if (Auth::user()->role != 'Customer') {
            return Redirect::to('/');
        }
        $data  = [
            'title' => 'Pembayaran Pesanan',
            'tryout' => DB::table('keranjang_order')->select('keranjang_order.*', 'produk_tryout.id as idProduk', 'produk_tryout.nama_tryout', 'produk_tryout.keterangan', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status')
                ->leftJoin('produk_tryout', 'keranjang_order.produk_tryout_id', '=', 'produk_tryout.id')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                ->where('keranjang_order.customer_id', '=', Auth::user()->customer_id)
                ->where('keranjang_order.id', '=', Crypt::decrypt($request->params))
                ->whereNot('kategori_produk.status', 'Gratis')->orderBy('keranjang_order.updated_at', 'DESC')->get()
        ];

        return view('main-web.produk.order-tryout', $data);
    }

    public function payOrder(Request $request)
    {
        // Cari data customer
        $customer = Customer::findOrFail(Auth::user()->customer_id);

        // Setup konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Cari data produk tryout
        $tryout = DB::table('keranjang_order')->select('keranjang_order.*', 'produk_tryout.id as idProduk', 'produk_tryout.nama_tryout', 'produk_tryout.keterangan', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'kategori_produk.judul', 'kategori_produk.status')
            ->leftJoin('produk_tryout', 'keranjang_order.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('keranjang_order.customer_id', '=', Auth::user()->customer_id)
            ->where('keranjang_order.id', '=', Crypt::decrypt($request->id))
            ->whereNot('kategori_produk.status', 'Gratis')->first();

        $referensiOrderID = $tryout->idProduk;
        $orderID = Str::uuid();

        // Cekk apakah harga ada promo
        if ($tryout->harga_promo != null) {
            $gross_amount = $tryout->harga_promo;
        } else {
            $gross_amount = $tryout->harga;
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $orderID,
                'gross_amount' => intval($gross_amount)
            ],
            'item_details' => [
                [
                    'id' => $referensiOrderID,
                    'price' => intval($gross_amount),
                    'quantity' => 1,
                    'name' => 'Pembayaran : ' . $tryout->nama_tryout
                ],
            ],
            'customer_details' => [
                'first_name' => $customer->nama_lengkap,
                'email' => Auth::user()->email,
                'phone' => $customer->kontak,
                'billing_address' => $customer->alamat
            ]
        ];

        $snapToken = Snap::getSnapToken($payload);

        try {
            // Buat Order
            $buatOrder = new OrderTryout();
            $buatOrder->id = $orderID;
            $buatOrder->faktur_id = 'F' . rand(1, 999);
            $buatOrder->customer_id = Auth::user()->customer_id;
            $buatOrder->nama = $customer->nama_lengkap;
            $buatOrder->produk_tryout_id = $referensiOrderID;

            if ($buatOrder->save()) {
                // Catata referral jika ada
                if ($request->referralCode) {
                    $referral = new ReferralCustomer();
                    $referral->id = rand(1, 999) . rand(1, 99);
                    $referral->kode_referral = $request->referralCode;
                    $referral->produk_tryout_id = $referensiOrderID;
                    $referral->save();
                }

                return response()->json([
                    'status'     => 'success',
                    'snap_token' => $snapToken,
                ]);
            } else {
                return response()->json([
                    'status'     => 'error',
                    'snap_token' => null
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    protected function simpanOrder($sendDataOrder)
    {
        // Cegah pesan produk yang sama
        $checkOrder = OrderTryout::where('produk_tryout_id', $sendDataOrder['produk_id'])->where('customer_id', $sendDataOrder['customer_id'])->first();
        if ($checkOrder) {
            return response()->json([
                'status' => 'error',
                'meesage' => 'Tidak bisa memesan produk yang sama !'
            ]);
        }

        // Buat payment id
        $orderID = Str::uuid();
        $payID = Str::uuid();
        // Buat Order
        $buatOrder = new OrderTryout();

        $buatOrder->id = $orderID;
        $buatOrder->faktur_id = 'F' . rand(1, 999);
        $buatOrder->customer_id = $sendDataOrder['customer_id'];
        $buatOrder->nama = $sendDataOrder['nama'];
        $buatOrder->produk_tryout_id = $sendDataOrder['produk_id'];
        $buatOrder->payment_id = $payID;

        $payment = new Payment();
        $payment->id = $payID;
        $payment->ref_order_id = $sendDataOrder['produk_id'];
        $payment->nominal = $sendDataOrder['nominal'];
        $payment->status = 'success';
        $payment->snap_token = $sendDataOrder['snap_token'];

        try {
            if ($payment->save() and $buatOrder->save()) {
                // Hapus keranjang
                $keranjang = KeranjangOrder::findOrFail($sendDataOrder['keranjang_id']);
                if ($keranjang) {
                    $keranjang->delete();
                }

                $order = DB::table('order_tryout')->select(
                    'order_tryout.*',
                    'produk_tryout.nama_tryout',
                    'produk_tryout.keterangan',
                    'pengaturan_tryout.harga',
                    'pengaturan_tryout.harga_promo',
                    'pengaturan_tryout.masa_aktif',
                    'payment.nominal'
                )->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
                    ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                    ->leftJoin('payment', 'produk_tryout.id', '=', 'payment.ref_order_id')
                    ->where('order_tryout.id', '=', $orderID)
                    ->where('customer_id', '=', Auth::user()->customer_id)->first();

                // Kirim email invoice
                Mail::to(Auth::user()->email)->send(new EmailFaktur($order));
            } else {
            }
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function daftarGratis(TryoutGratisRequest $request): RedirectResponse
    {
        // Cek apakah sudah pernah coba gratis
        $cekGratisan = LimitTryout::where('customer_id', Auth::user()->customer_id)->first();
        if ($cekGratisan) {
            return redirect()->route('mainweb.daftar-tryout-gratis')->with('errorMessage', 'Pendaftaran tryout gratis hanya boleh 1 kali !');
        }

        // Bukti share produk
        $fileBuktiShare = $request->file('buktiShare');
        $hasnameBuktiShare = $fileBuktiShare->hashName();

        // Bukti share follow
        $fileBuktiFollow = $request->file('buktiFollow');
        $hasnameBuktiFollow = $fileBuktiFollow->hashName();

        $fileUploadBuktiShare = $fileBuktiShare->storeAs('public\share-follow', $hasnameBuktiShare);
        $fileUploadBuktiFollow = $fileBuktiShare->storeAs('public\share-follow', $hasnameBuktiFollow);

        if (!$fileUploadBuktiShare and $fileUploadBuktiFollow) {
            return redirect()->back()->with('errorMessage', 'Unggah bukti share dan follow gagal !')->withInput();
        }

        $limitTryout = new LimitTryout();
        $limitTryout->id = rand(1, 999) . rand(1, 99);
        $limitTryout->customer_id = Auth::user()->customer_id;
        $limitTryout->bukti_share = $hasnameBuktiShare;
        $limitTryout->bukti_follow = $hasnameBuktiFollow;
        $limitTryout->informasi = htmlspecialchars($request->input('informasi'));
        $limitTryout->alasan = htmlspecialchars($request->input('alasan'));
        $limitTryout->status_validasi = 'Menunggu';

        if ($limitTryout->save()) {
            return redirect()->route('mainweb.daftar-tryout-gratis')->with('successMessage', 'Pendaftaran berhasil silahkan cek email secara berkala untuk informasi persetujuan dari kami !');
        } else {
            return redirect()->back()->with('errorMessage', 'Pendaftaran gagal, silahkan coba lagi !')->withInput();
        }
    }

    public function pesanTryoutGratis(Request $request)
    {
        // Cek apakah sudah pernah coba gratis
        $tryoutGratis = LimitTryout::where('customer_id', Auth::user()->customer_id)->first();
        if ($tryoutGratis->produk_tryout_id != null) {
            return redirect()->route('site.main');
        }

        $tryoutGratis->produk_tryout_id = Crypt::decrypt($request->idProdukTryout);

        if ($tryoutGratis->save()) {
            return redirect()->route('site.tryout-gratis');
        } else {
            return redirect()->back()->with('errorMessage', 'Produk tryout gratis gagal dipilih !')->withInput();
        }
    }

    public function checkReferral(Request $request)
    {
        $request->validate(
            [
                'kodeReferral' => ['string', 'max:255'],
            ],
            [
                'kodeReferral.string' => 'Kode Referral harus berupa kalimat !',
                'kodeReferral.max' => 'Kode Referral maksimal 255 karakter'
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
}
