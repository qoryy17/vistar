<?php

namespace App\Http\Controllers\Landing;

use App\Enums\UserRole;
use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\FotoRequest;
use App\Http\Requests\Customer\ProfilRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
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
                return Redirect::route('mainweb.profile')->with('profilMessage', 'Foto berhasil disimpan !');
            } else {
                return Redirect::route('mainweb.profile')->with('errorMessage', 'Foto gagal disimpan !');
            }
        }
        return back()->with('errorMessage', 'Unggah foto gagal !')->withInput();
    }

    public function ubahProfil(ProfilRequest $request)
    {
        $request->validated();

        $user = Auth::user();

        if ($user->role === UserRole::CUSTOMER->value) {
            $customer = Customer::findOrFail($user->customer_id);

            // Ubah format tanggal menjadi YYYY/MM/DD
            $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', htmlspecialchars($request->input('tanggalLahir')))->format('Y-m-d');

            $updateCustomer = $customer->update([
                'nama_lengkap' => htmlspecialchars($request->input('namaLengkap')),
                'tanggal_lahir' => $formattedDate,
                'jenis_kelamin' => htmlspecialchars($request->input('jenisKelamin')),
                'kontak' => htmlspecialchars($request->input('kontak')),
                'alamat' => htmlspecialchars($request->input('alamat')),
                'provinsi' => htmlspecialchars($request->input('provinsi')),
                'kabupaten' => htmlspecialchars($request->input('kotaKab')),
                'kecamatan' => strtoupper(htmlspecialchars($request->input('kecamatan'))),
                'pendidikan' => htmlspecialchars($request->input('pendidikan')),
                'jurusan' => htmlspecialchars($request->input('jurusan')),
            ]);
            if (!$updateCustomer) {
                return redirect()->route('mainweb.profile')->with('errorMessage', 'Profil gagal disimpan !');
            }
        }

        $updateUser = User::where('id', Auth::id())
            ->update([
                'name' => htmlspecialchars($request->input('namaLengkap')),
            ]);
        if (!$updateUser) {
            return redirect()->route('mainweb.profile')->with('errorMessage', 'Profil gagal disimpan !');
        }

        // Simpan logs aktivitas pengguna
        $logs = htmlspecialchars($request->input('namaLengkap')) . ' telah melengkapi data profil akun waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return Redirect::route('mainweb.profile')->with('profilMessage', 'Profil berhasil disimpan !');
    }

    public function ubahPassword(Request $request) //: RedirectResponse
    {
        // Autentifikasi user
        $user = Auth::user();

        if ($user->google_id != null) {
            if ($request->filled('passwordBaru')) {
                $request->validate([
                    'passwordBaru' => [
                        'string',
                        'min:8',
                        'regex:/[A-Z]/', // must contain at least one uppercase letter
                        'regex:/[a-z]/', // must contain at least one lowercase letter
                        'regex:/[0-9]/', // must contain at least one digit
                        'regex:/[@$!%*?&]/', // must contain a special character,
                        'same:konfirmasiPassword', // mencocokkan dengan konfirmasi password
                    ],
                ], [
                    'passwordBaru.min' => 'Password harus mengandung 8 karakter.',
                    'passwordBaru.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
                    'passwordBaru.same' => 'Password konfirmasi tidak cocok',
                ]);

                $passwordUser = User::findOrFail($user->id);
                $passwordUser->password = Hash::make($request->input('passwordBaru'));

                $passwordUser->save();
                // // Simpan logs aktivitas pengguna
                $logs = Auth::user()->name . ' telah memperbarui password waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return redirect()->route('mainweb.profile')->with('profilMessage', 'Password berhasil disimpan !');
            }
        } else {
            if ($request->filled('passwordBaru')) {
                if ($request->filled('passwordLama')) {
                    $request->validate([
                        'passwordLama' => [
                            'string',
                        ],
                        'passwordBaru' => [
                            'string',
                            'min:8',
                            'regex:/[A-Z]/', // must contain at least one uppercase letter
                            'regex:/[a-z]/', // must contain at least one lowercase letter
                            'regex:/[0-9]/', // must contain at least one digit
                            'regex:/[@$!%*?&]/', // must contain a special character,
                            'same:konfirmasiPassword', // mencocokkan dengan konfirmasi password
                        ],
                    ], [
                        'passwordBaru.min' => 'Password harus mengandung 8 karakter.',
                        'passwordBaru.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
                        'passwordBaru.same' => 'Password konfirmasi tidak cocok',
                    ]);

                    if (!Hash::check($request->passwordLama, $user->password)) {
                        throw ValidationException::withMessages([
                            'passwordLama' => 'Password lama tidak cocok.',
                        ]);
                    }

                    $passwordUser = User::findOrFail($user->id);
                    $passwordUser->password = Hash::make($request->input('passwordBaru'));

                    $passwordUser->save();
                    // // Simpan logs aktivitas pengguna
                    $logs = Auth::user()->name . ' telah memperbarui password waktu tercatat :  ' . now();
                    RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                    return redirect()->route('mainweb.profile')->with('profilMessage', 'Password berhasil disimpan !');
                }
            }
        }
        return redirect()->route('mainweb.profile')->with('profilMessage', 'Password tidak diubah !');
    }
}
