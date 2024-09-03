<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\TryoutGratisRequest;
use App\Mail\EmailFaktur;
use App\Models\Customer;
use App\Models\KeranjangOrder;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use App\Models\Payment;
use App\Models\ReferralCustomer;
use App\Models\User;
use App\Services\Payment\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

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

        $grossAmount = $tryout->harga;
        // Cekk apakah harga ada promo
        if ($tryout->harga_promo != null) {
            $grossAmount = $tryout->harga_promo;
        }
        $grossAmount = intval($grossAmount);

        $midtransService = new MidtransService();
        $snapToken = $midtransService->createOrder([
            'orderID' => $orderID,
            'grossAmount' => $grossAmount,
            'customerFullName' => $customer->nama_lengkap,
            'customerEmail' => Auth::user()->email,
            'customerPhone' => $customer->kontak,
            'customerBillingAddress' => $customer->alamat,
            'itemDetails' => [
                [
                    'id' => $referensiOrderID,
                    'price' => intval($grossAmount),
                    'quantity' => 1,
                    'name' => 'Pembayaran : ' . $tryout->nama_tryout,
                ],
            ],
        ]);

        try {
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
                return response()->json([
                    'status' => 'error',
                    'snap_token' => null,
                ]);
            }

            // Add Payment Data
            Payment::create([
                'id' => Str::uuid(),
                'customer_id' => Auth::user()->customer_id,
                'ref_order_id' => $orderID,
                'snap_token' => $snapToken,
                'transaksi_id' => null,
                'nominal' => $grossAmount,
                'status_transaksi' => $status,
            ]);

            // Catata referral jika ada : note : referral disable sementara
            /*if ($request->referralCode) {
                $referral = new ReferralCustomer();
                $referral->id = rand(1, 999) . rand(1, 99);
                $referral->kode_referral = $request->referralCode;
                $referral->produk_tryout_id = $referensiOrderID;
                $referral->save();
            }*/

            // Hapus Keranjang
            $keranjang = KeranjangOrder::find(Crypt::decrypt($request->id));
            if ($keranjang) {
                $keranjang->delete();
            }

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
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
        $fileUploadBuktiFollow = $fileBuktiFollow->storeAs('public\share-follow', $hasnameBuktiFollow);

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
            return redirect()->route('mainweb.daftar-tryout-gratis')->with('successMessage', 'Pendaftaran berhasil silahkan cek email secara berkala untuk informasi persetujuan dari kami. Maksimal verifikasi 1x24 oleh Admin !');
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
