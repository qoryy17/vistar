<?php

namespace App\Http\Controllers\Panel;

use App\Models\User;
use App\Models\Customer;
use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OrderTryout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class Customers extends Controller
{
    public function index()
    {

        $customer = DB::table('customer')->select('customer.*', 'users.email', 'users.kode_referral', 'users.blokir', 'users.id as user_id')->leftJoin('users', 'customer.id', '=', 'users.customer_id')->where('users.role', '=', 'Customer')->get();
        // Caching data provinsi, kabupaten, dan kecamatan untuk menghindari panggilan API berulang
        $provinsi = Cache::remember('provinsi', 3600, function () {
            return Http::get('https://ibnux.github.io/data-indonesia/provinsi.json')->json();
        });

        $kabupaten = [];
        $kecamatan = [];

        $customerAll = [];

        foreach ($customer as $row) {
            if (!empty($row->provinsi)) {
                if (!isset($kabupaten[$row->provinsi])) {
                    $kabupaten[$row->provinsi] = Cache::remember("kabupaten_{$row->provinsi}", 3600, function () use ($row) {
                        return Http::get('https://ibnux.github.io/data-indonesia/kabupaten/' . $row->provinsi . '.json')->json();
                    });
                }
            }

            if (!empty($row->kabupaten)) {
                if (!isset($kecamatan[$row->kabupaten])) {
                    $kecamatan[$row->kabupaten] = Cache::remember("kecamatan_{$row->kabupaten}", 3600, function () use ($row) {
                        return Http::get('https://ibnux.github.io/data-indonesia/kecamatan/' . $row->kabupaten . '.json')->json();
                    });
                }
            }

            $selectedProvinsi = !empty($row->provinsi) ? collect($provinsi)->firstWhere('id', $row->provinsi) : null;
            $selectedKabupaten = !empty($row->kabupaten) ? collect($kabupaten[$row->provinsi] ?? [])->firstWhere('id', $row->kabupaten) : null;
            $selectedKecamatan = !empty($row->kecamatan) ? collect($kecamatan[$row->kabupaten] ?? [])->firstWhere('id', $row->kecamatan) : null;

            $customerAll[] = [
                'id' => $row->id,
                'nama_lengkap' => $row->nama_lengkap,
                'tanggal_lahir' => $row->tanggal_lahir,
                'jenis_kelamin' => $row->jenis_kelamin,
                'kontak' => $row->kontak,
                'alamat' => $row->alamat,
                'provinsi' => is_null($row->provinsi) ? '' : $selectedProvinsi['nama'],
                'kabupaten' => is_null($row->kabupaten) ? '' : $selectedKabupaten['nama'],
                'kecamatan' => is_null($row->kecamatan) ? '' : $selectedKecamatan['nama'],
                'pendidikan' => $row->pendidikan,
                'jurusan' => $row->jurusan,
                'foto' => $row->foto,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
                'email' => $row->email,
                'kode_refferal' => $row->kode_referral,
                'blokir' => $row->blokir,
                'user_id' => $row->user_id
            ];
        }
        $data = [
            'form_title' => 'Data Customer',
            'page_title' => 'Customer',
            'breadcumb' => 'Manajemen Customer',
            'customer' => $customerAll,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];

        return view('main-panel.customer.data-customer', $data);
    }

    public function hapusCustomer(Request $request): RedirectResponse
    {
        $user = User::findOrFail(Crypt::decrypt($request->id));
        if ($user) {
            // Cek apakah pernah memesan produk tryout ?
            $tryout = OrderTryout::where('customer_id', $user->customer_id)->first();
            if ($tryout) {
                return Redirect::route('customer.main')->with('error', 'Customer pemesan produk tidak bisa dihapus !');
            }
            $users = Auth::user();
            $user->delete();
            $customer = Customer::find($user->customer_id);
            if ($customer) {
                $customer->delete();
            }
            // Simpan logs aktivitas pengguna
            $logs = $users->name . ' telah menghapus customer dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('customer.main')->with('message', 'Customer berhasil dihapus !');
        }
        return Redirect::route('customer.main')->with('error', 'Customer gagal dihapus !');
    }

    public function blokirCustomer(Request $request): RedirectResponse
    {
        $user = User::findOrFail(Crypt::decrypt($request->id));
        if ($user) {
            $users = Auth::user();
            if ($user->blokir == 'Y') {
                $user->blokir = 'T';
                $user->save();
                // Simpan logs aktivitas pengguna
                $logs = $users->name . ' telah memblokir customer dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::route('customer.main')->with('message', 'Customer berhasil diblokir !');
            } else {
                $user->blokir = 'Y';
                $user->save();
                // Simpan logs aktivitas pengguna
                $logs = $users->name . ' telah membuka blokir customer dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::route('customer.main')->with('message', 'Customer berhasil diunblokir !');
            }
        }
        return Redirect::route('customer.main')->with('error', 'Customer gagal diubah !');
    }
}
