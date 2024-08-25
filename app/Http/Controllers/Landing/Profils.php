<?php

namespace App\Http\Controllers\Landing;

use App\Models\User;
use App\Models\Customer;
use App\Helpers\RecordLogs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Customer\FotoRequest;
use App\Http\Requests\Customer\ProfilRequest;
use Illuminate\Validation\ValidationException;

class Profils extends Controller
{
    public function ubahFoto(FotoRequest $request): RedirectResponse
    {
        $request->validated();

        $fileFoto = $request->file('foto');
        $fileHashname = $fileFoto->hashName();

        $fileUpload = $fileFoto->storeAs('public\user', $fileHashname);

        if ($fileUpload) {
            $user = Customer::findOrFail(Auth::user()->customer_id);

            $user->foto = $fileHashname;

            // Hapus foto yang lama
            Storage::disk('public')->delete('public/user' . $user->foto);

            if ($user->save()) {
                session(['customer' => $user]);
                // // Simpan logs aktivitas pengguna
                $logs = Auth::user()->name . ' telah melengkapi foto profil akun waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::to('/profil-saya')->with('profilMessage', 'Foto berhasil disimpan !');
            } else {
                return Redirect::to('/profil-saya')->with('errorMessage', 'Foto gagal disimpan !');
            }
        }
        return back()->with('errorMessage', 'Unggah foto gagal !')->withInput();
    }

    public function ubahProfil(ProfilRequest $request)
    {
        // Autentifikasi user
        $users = Auth::user();
        $request->validated();

        $user = Customer::findOrFail($users->customer_id);
        $user->nama_lengkap = htmlspecialchars($request->input('namaLengkap'));

        // Ubah format tanggal menjadi YYYY/MM/DD
        $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', htmlspecialchars($request->input('tanggalLahir')))->format('Y-m-d');

        $user->tanggal_lahir = $formattedDate;
        $user->jenis_kelamin = htmlspecialchars($request->input('jenisKelamin'));
        $user->kontak = htmlspecialchars($request->input('kontak'));
        $user->alamat = htmlspecialchars($request->input('alamat'));
        $user->provinsi = htmlspecialchars($request->input('provinsi'));
        $user->kabupaten = htmlspecialchars($request->input('kotaKab'));
        $user->kecamatan = strtoupper(htmlspecialchars($request->input('kecamatan')));
        $user->pendidikan = htmlspecialchars($request->input('pendidikan'));
        $user->jurusan = htmlspecialchars($request->input('jurusan'));

        $akun = User::findOrFail($users->id);

        $akun->name = htmlspecialchars($request->input('namaLengkap'));

        if ($user->save() and $akun->save()) {
            // // Simpan logs aktivitas pengguna
            $logs = htmlspecialchars($request->input('namaLengkap')) . ' telah melengkapi data profil akun waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::to('/profil-saya')->with('profilMessage', 'Profil berhasil disimpan !');
        } else {
            return Redirect::to('/profil-saya')->with('errorMessage', 'Profil gagal disimpan !');
        }
    }

    public function ubahPassword(Request $request): RedirectResponse
    {
        // Autentifikasi user
        $user = Auth::user();

        if ($request->filled('passwordLama')) {
            $request->validate([
                'passwordLama' => [
                    'password',
                    'string',
                ],
                'passwordBaru' => [
                    'password',
                    'confirmed',
                    'string',
                    'min:8',
                    'string',
                    'regex:/[A-Z]/',       // must contain at least one uppercase letter
                    'regex:/[a-z]/',       // must contain at least one lowercase letter
                    'regex:/[0-9]/',       // must contain at least one digit
                    'regex:/[@$!%*?&]/'   // must contain a special character
                ],
            ], [
                'passwordBaru.min' => 'Password harus mengandung 8 karakter.',
                'passwordBaru.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
                'passwordBaru.confirmed' => 'Password konfirmasi tidak cocok'
            ]);

            if (!Hash::check($request->passwordLama, $user->password)) {
                throw ValidationException::withMessages([
                    'passwordLama' => 'Password lama tidak cocok.',
                ]);
            }
            if ($request->filled('passwordBaru')) {

                $passwordUser = User::findOrFail($user->id);
                $user->password = Hash::make($request->passwordBaru);

                $passwordUser->save();
                // // Simpan logs aktivitas pengguna
                $logs = Auth::user()->name . ' telah memperbarui password waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return redirect()->route('mainweb.profil-saya')->with('profilMessage', 'Password berhasil disimpan !');
            }
        }
        return redirect()->route('mainweb.profil-saya')->with('profilMessage', 'Password tidak diubah !');
    }
}
