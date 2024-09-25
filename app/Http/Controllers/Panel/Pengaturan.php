<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\PengaturanRequest;
use App\Models\LimitTryout;
use App\Models\Logs;
use App\Models\PengaturanWeb;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class Pengaturan extends Controller
{
    public function formBanner($param = null, $id = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Banner Carousel';
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Banner Carousel';
        } else {
            return Redirect::route('main.banner')->with('error', 'Parameter tidak valid !');
        }
        $data = [
            'form_title' => $form_title,
            'page_title' => 'Banner Carousel',
            'breadcumb' => $form_title,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
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
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.pengaturan.form-faq-web', $data);
    }

    public function simpanPengaturanWeb(PengaturanRequest $request)
    {
        $request->validated();

        // Cek apakah sudah terdapat pengaturan
        $checkPengaturanWeb = PengaturanWeb::first();

        $savedData = [
            'nama_bisnis' => htmlspecialchars($request->input('namaBisnis')),
            'tagline' => htmlspecialchars($request->input('tagline')),
            'perusahaan' => htmlspecialchars($request->input('perusahaan')),
            'alamat' => htmlspecialchars($request->input('alamat')),
            'email' => htmlspecialchars($request->input('email')),
            'facebook' => htmlspecialchars($request->input('facebook')),
            'instagram' => htmlspecialchars($request->input('instagram')),
            'kontak' => htmlspecialchars($request->input('kontak')),
            'meta_author' => htmlspecialchars($request->input('metaAuthor')),
            'meta_keyword' => htmlspecialchars($request->input('metaKeyword')),
            'meta_description' => htmlspecialchars($request->input('metaDescription')),
        ];

        $oldLogo = null;
        $uploadedNewLogo = false;
        if ($checkPengaturanWeb) {
            $oldLogo = $checkPengaturanWeb->logo;
        }

        $uploadedFiles = [];

        try {
            DB::beginTransaction();

            if ($request->hasFile('logo')) {
                // Upload new logo
                $fileLogo = $request->file('logo');

                $uploadedFile = 'images/config/logo-' . $fileLogo->hashName();

                $fileUpload = $fileLogo->storeAs('public', $uploadedFile);
                if (!$fileUpload) {
                    throw new Exception('Unggah logo gagal !');
                }

                array_push($uploadedFiles, $uploadedFile);
                $uploadedNewLogo = true;

                $savedData['logo'] = $uploadedFile;
            }

            $save = null;
            if ($checkPengaturanWeb) {
                $save = $checkPengaturanWeb->update($savedData);
            } else {
                $save = $checkPengaturanWeb->create($savedData);
            }

            if (!$save) {
                throw new Exception('Pengaturan gagal diperbarui.');
            }

            Cache::forget('app:web_setting');

            // Simpan logs aktivitas pengguna
            $logs = Auth::user()->name . ' telah memperbarui pengaturan web waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

            DB::commit();

            // Delete old logo
            if ($uploadedNewLogo && !is_null($oldLogo) && $oldLogo !== '' && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            return redirect()->route('main.pengaturan')->with('message', 'Pengaturan berhasil diperbarui !');
        } catch (\Throwable $th) {
            DB::rollback();

            foreach ($uploadedFiles as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            return redirect()->back()->with('error', $th->getMessage())->withInput();
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
                    'email.email' => 'Email harus valid',
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
                        'regex:/[A-Z]/', // must contain at least one uppercase letter
                        'regex:/[a-z]/', // must contain at least one lowercase letter
                        'regex:/[0-9]/', // must contain at least one digit
                        'regex:/[@$!%*?&]/', // must contain a special character
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
                    'password.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
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
