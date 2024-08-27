<?php

namespace App\Http\Controllers\Panel;

use App\Models\Logs;
use App\Models\User;
use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use App\Models\PengaturanWeb;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Panel\PengaturanRequest;

class Pengaturan extends Controller
{
    public function formBanner($param = null, $id = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Banner Carousel';
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Banner Carousel';
        } else {
            return Redirect::to('/banner')->with('error', 'Parameter tidak valid !');
        }
        $data = [
            'form_title' => $form_title,
            'page_title' => 'Banner Carousel',
            'breadcumb' => $form_title,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.pengaturan.form-banner-web', $data);
    }

    public function formFaq($param = null, $id = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah FAQ';
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit FAQ';
        } else {
            return Redirect::route('main.faq')->with('error', 'Parameter tidak valid !');
        }
        $data = [
            'form_title' => $form_title,
            'page_title' => 'FAQ',
            'breadcumb' => $form_title,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.pengaturan.form-faq-web', $data);
    }

    public function simpanPengaturanWeb(PengaturanRequest $request)
    {

        $cekPengaturan = PengaturanWeb::all()->first();
        if ($cekPengaturan) {
            // $cekPengaturan->delete();

        }
        $request->validated();

        // Cek apakah sudah terdapat pengaturan

        $checkPengaturan = PengaturanWeb::all()->first();
        if ($checkPengaturan) {
            $cekPengaturan->delete();
        }

        $pengaturanWeb = new PengaturanWeb();
        $pengaturanWeb->id = rand(1, 99);
        $pengaturanWeb->nama_bisnis = htmlspecialchars($request->input('namaBisnis'));
        $pengaturanWeb->tagline = htmlspecialchars($request->input('tagline'));
        $pengaturanWeb->perusahaan = htmlspecialchars($request->input('perusahaan'));
        $pengaturanWeb->alamat = htmlspecialchars($request->input('alamat'));
        $pengaturanWeb->email = htmlspecialchars($request->input('email'));
        $pengaturanWeb->facebook = htmlspecialchars($request->input('facebook'));
        $pengaturanWeb->instagram = htmlspecialchars($request->input('instagram'));
        $pengaturanWeb->kontak = htmlspecialchars($request->input('kontak'));
        if ($request->hasFile('logo')) {
            // Hapus logo lama
            Storage::disk('public')->delete($request->input('oldLogo'));
            // Upload logo baru
            $fileLogo = $request->file('logo');
            $fileHashname = $fileLogo->hashName();

            $fileUpload = $fileLogo->storeAs('public', $fileHashname);
            if (!$fileUpload) {
                return Redirect::route('main.pengaturan')->with('error', 'Unggah logo gagal !');
            }
            $pengaturanWeb->logo = $fileHashname;
        } else {
            $pengaturanWeb->logo = $request->input('oldLogo');
        }

        $pengaturanWeb->meta_author = htmlspecialchars($request->input('metaAuthor'));
        $pengaturanWeb->meta_keyword = htmlspecialchars($request->input('metaKeyword'));
        $pengaturanWeb->meta_description = htmlspecialchars($request->input('metaDescription'));

        if ($pengaturanWeb->save()) {
            // Simpan logs aktivitas pengguna
            $logs = Auth::user()->name . ' telah memperbarui pengaturan web waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('main.pengaturan')->with('message', 'Pengaturan berhasil dihapus !');
        } else {
            return Redirect::route('main.pengaturan')->with('error', 'Pengaturan gagal dihapus !');
        }
    }

    public function hapusLogs(Request $request)
    {
        $logs = Logs::findOrFail(Crypt::decrypt($request->id));
        if ($logs) {
            $logs->delete();
            return Redirect::route('main.logs')->with('message', 'Log berhasil dihapus !');
        } else {
            return Redirect::route('main.logs')->with('error', 'Log gagal dihapus !');
        }
    }

    public function updateProfil(Request $request)
    {
        if (!$request->input('password')) {
            $request->validate(
                [
                    'namaLengkap' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email'],
                ],
                [
                    'namaLengkap.required' => 'Nama lengkap wajib di isi',
                    'namaLengkap.string' => 'Nama lengkap harus berupa kalimat',
                    'email.required' => 'Email wajib di isi',
                    'email.email' => 'Email harus valid'
                ]
            );
        } else {
            $request->validate(
                [
                    'namaLengkap' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email'],
                    'password' => [
                        'required',
                        'min:8',
                        'string',
                        'regex:/[A-Z]/',       // must contain at least one uppercase letter
                        'regex:/[a-z]/',       // must contain at least one lowercase letter
                        'regex:/[0-9]/',       // must contain at least one digit
                        'regex:/[@$!%*?&]/'   // must contain a special character
                    ],
                ],
                [
                    'namaLengkap.required' => 'Nama lengkap wajib di isi',
                    'namaLengkap.string' => 'Nama lengkap harus berupa kalimat',
                    'email.required' => 'Email wajib di isi',
                    'email.email' => 'Email harus valid',
                    'password.required' => 'Password wajib di isi',
                    'password.min' => 'Password harus mengandung 8 karakter',
                    'password.string' => 'Password harus berupa kalimat',
                    'password.regex' => 'Password harus mengandung huruf kapital, angka dan karakter'
                ]
            );
        }


        $pengguna = User::findOrFail(Crypt::decrypt($request->input('userID')));
        $pengguna->name = htmlspecialchars($request->input('namaLengkap'));
        $pengguna->email = htmlspecialchars($request->input('email'));
        if ($request->input('password')) {
            $pengguna->password = htmlspecialchars($request->input('password'));
        }
        if ($pengguna->save()) {
            // Simpan logs aktivitas pengguna
            $logs = Auth::user()->name . ' telah memperbarui profil dengan ID ' . Crypt::decrypt($request->input('userID')) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('main.profil-admin')->with('message', 'Profil berhasil diperbarui !');
        } else {
            return Redirect::route('main.profil-admin')->with('error', 'Profil gagal diperbarui !');
        }
    }
}
