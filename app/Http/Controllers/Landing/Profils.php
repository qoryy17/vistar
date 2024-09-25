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
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Profils extends Controller
{
    public function ubahFoto(FotoRequest $request): RedirectResponse
    {
        $request->validated();

        $fileFoto = $request->file('foto');
        $fileHashname = $fileFoto->hashName();

        $fileUpload = $fileFoto->storeAs('public/user', $fileHashname);

        if (!$fileUpload) {
            return back()->with('errorMessage', 'Unggah foto gagal !')->withInput();
        }

        $customer = Customer::findOrFail(Auth::user()->customer_id);

        $oldPhoto = 'user/' . $customer->foto;
        // Hapus foto yang lama
        if (Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        $save = $customer->update([
            'foto' => $fileHashname
        ]);

        if (!$save) {
            return back()->with('errorMessage', 'Foto gagal disimpan !');
        }

        // Simpan logs aktivitas pengguna
        $logs = Auth::user()->name . ' telah melengkapi foto profil akun waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        $nextUrl = request()->get('next-url');
        if ($nextUrl) {
            if (filter_var($nextUrl, FILTER_VALIDATE_URL)) {
                // Check if next url not outside of the domain
                if (\App\Helpers\Common::isSameDomainFromURL(request()->getHttpHost(), $nextUrl)) {
                    return redirect()->to($nextUrl);
                }
            }
        }

        return redirect()->route('mainweb.profile', ['next-url' => request()->get('next-url')])->with('profilMessage', 'Foto berhasil disimpan !');
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
                return back()->with('errorMessage', 'Profil gagal disimpan !')->withInput();
            }
        }

        $updateUser = User::where('id', Auth::id())
            ->update([
                'name' => htmlspecialchars($request->input('namaLengkap')),
            ]);
        if (!$updateUser) {
            return back()->with('errorMessage', 'Profil gagal disimpan !')->withInput();
        }

        // Simpan logs aktivitas pengguna
        $logs = htmlspecialchars($request->input('namaLengkap')) . ' telah melengkapi data profil akun waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        $nextUrl = request()->get('next-url');
        if ($nextUrl) {
            if (filter_var($nextUrl, FILTER_VALIDATE_URL)) {
                // Check if next url not outside of the domain
                if (\App\Helpers\Common::isSameDomainFromURL(request()->getHttpHost(), $nextUrl)) {
                    return redirect()->to($nextUrl);
                }
            }
        }

        return redirect()->route('mainweb.profile', ['next-url' => request()->get('next-url')])->with('profilMessage', 'Profil berhasil disimpan !');
    }

    public function ubahPassword(Request $request): RedirectResponse
    {
        // Autentifikasi user
        $user = Auth::user();

        $rules = [
            'passwordBaru' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*?&]/', // must contain a special character,
                'same:konfirmasiPassword', // mencocokkan dengan konfirmasi password
            ],
        ];
        $validationMessage = [
            'passwordLama.required' => 'Password lama diperlukan.',
            'passwordLama.string' => 'Password lama harus berupa karakter.',
            'passwordBaru.required' => 'Password baru diperlukan.',
            'passwordBaru.string' => 'Password baru harus berupa karakter.',
            'passwordBaru.min' => 'Password baru harus mengandung 8 karakter.',
            'passwordBaru.regex' => 'Password baru harus mengandung huruf kapital, angka dan karakter',
            'passwordBaru.same' => 'Konfirmasi Password baru tidak cocok',
        ];

        $requiredOldPassword = ($user->google_id == null && $user->password != null) ||
            ($user->google_id != null && $user->password != null);

        if ($requiredOldPassword) {
            $rules['passwordLama'] = ['required', 'string'];
        }

        $request->validate($rules, $validationMessage);

        if ($requiredOldPassword && !Hash::check($request->passwordLama, $user->password)) {
            throw ValidationException::withMessages([
                'passwordLama' => 'Password lama tidak cocok.',
            ]);
        }

        // check if there is no changes
        if (Hash::check($request->passwordBaru, $user->password)) {
            return redirect()->back()->with('profilMessage', 'Password tidak ada perubahan !');
        }

        $passwordUser = User::findOrFail($user->id);
        $passwordUser->password = Hash::make($request->input('passwordBaru'));

        $save = $passwordUser->save();
        if (!$save) {
            return redirect()->back()->with('profilMessage', 'Password gagal disimpan !');
        }

        // Simpan logs aktivitas pengguna
        $logs = Auth::user()->name . ' telah memperbarui password waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('mainweb.profile')->with('profilMessage', 'Password berhasil disimpan !');
    }
}
