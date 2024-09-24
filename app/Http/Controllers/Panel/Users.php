<?php

namespace App\Http\Controllers\Panel;

use App\Enums\UserRole;
use App\Models\User;
use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Panel\UserRequest;
use Illuminate\Support\Facades\Redirect;

class Users extends Controller
{
    public function index()
    {
        $data = [
            'form_title' => 'Data Pengguna',
            'page_title' => 'Pengguna',
            'breadcumb' => 'Manajemen Pengguna',
            'users' => User::whereNotIn('role', ['Customer']),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.users.data-users', $data);
    }

    public function formUser($param = null, $id = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Pengguna';
            $formParam = Crypt::encrypt('add');
            $user = '';
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Pengguna';
            $formParam = Crypt::encrypt('update');
            $user = User::findOrFail(Crypt::decrypt($id));
        } else {
            return Redirect::route('user.main')->with('error', 'Parameter tidak valid !');
        }
        $data = [
            'form_title' => $form_title,
            'page_title' => 'Pengguna',
            'breadcumb' => $form_title,
            'users' => $user,
            'formParam' => $formParam,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.users.form-users', $data);
    }

    public function simpanUser(UserRequest $request): RedirectResponse
    {
        // Autentifikasi user
        $users = Auth::user();
        if (Crypt::decrypt($request->input('formParameter')) == 'add') {
            $request->validate([
                'namaLengkap' => ['required'],
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
                'role' => ['required'],
                'blokir' => ['required']
            ], [
                'namaLengkap.required' => 'Nama lengkap wajib di isi',
                'email.required' => 'Email wajib di isi',
                'email.email' => 'Email harus valid',
                'role' => 'Role wajib di pilih',
                'blokir' => 'Blokir wajib di pilih',
                'password.min' => 'Password harus mengandung 8 karakter.',
                'password.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
            ]);

            $user = new User();
            $user->name = ucwords(htmlspecialchars($request->input('namaLengkap')));
            $user->email = htmlspecialchars($request->input('email'));
            $user->email_verified_at = now();
            $user->password = Hash::make(htmlspecialchars($request->input('password')));
            $user->role = htmlspecialchars($request->input('role'));
            $user->blokir = htmlspecialchars($request->input('blokir'));

            // Catatan log
            $logs = $users->name . ' telah menambahkan pengguna ' . htmlspecialchars($request->input('namaTryout')) . ' waktu tercatat :  ' . now();
            $message = 'Pengguna berhasil disimpan !';
            $error = 'Pengguna gagal disimpan !';
        } elseif (Crypt::decrypt($request->input('formParameter')) == 'update') {
            if ($request->input('password')) {
                $request->validated();
            } else {
                $request->validate(
                    [
                        'namaLengkap' => ['required'],
                        'email' => ['required', 'email'],
                        'role' => ['required'],
                        'blokir' => ['required']
                    ],
                    [
                        'namaLengkap.required' => 'Nama lengkap wajib di isi',
                        'email.required' => 'Email wajib di isi',
                        'email.email' => 'Email harus valid',
                        'role' => 'Role wajib di pilih',
                        'blokir' => 'Blokir wajib di pilih'
                    ]
                );
            }

            $user = User::findOrFail($request->input('userID'));
            $user->name = ucwords(htmlspecialchars($request->input('namaLengkap')));
            $user->email = htmlspecialchars($request->input('email'));
            if ($request->input('password')) {
                $user->password = Hash::make(htmlspecialchars($request->input('password')));
            }
            $user->role = htmlspecialchars($request->input('role'));
            $user->blokir = htmlspecialchars($request->input('blokir'));

            // Catatan log
            $logs = $users->name . ' telah memperbarui pengguna dengan ID ' . $request->input('userID') . ' waktu tercatat :  ' . now();
            $message = 'Pengguna berhasil diperbarui !';
            $error = 'Pengguna gagal diperbarui !';
        } else {
            return Redirect::route('user.main')->with('error', 'Parameter tidak valid !');
        }

        if ($user->save()) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('user.main')->with('message', $message);
        } else {
            return Redirect::route('user.main')->with('error', $error)->withInput();
        }
    }

    public function ubahPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'userID' => ['required'],
            'password' => ['required', 'string', 'min:8']
        ]);

        $users = Auth::user();
        $user = User::findOrFail($request->input('userID'));
        $user->password = Hash::make($request->input('password'));

        if ($user->save()) {
            // Simpan logs aktivitas pengguna
            $logs = $users->name . ' telah memperbarui password pengguna dengan ID ' . $request->input('userID') . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('user.main')->with('message', 'Password berhasil diubah');
        }
        return Redirect::route('user.main')->with('error', 'Password gagal diubah !');
    }

    public function hapusUsers(Request $request): RedirectResponse
    {
        $deletedUser = User::findOrFail(Crypt::decrypt($request->id));
        if (!$deletedUser) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan !');
        }

        $user = Auth::user();
        if ($deletedUser->role === UserRole::SUPER_ADMIN->value) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus jenis pengguna ini !');
        }

        $deleted = $deletedUser->delete();
        if (!$deleted) {
            return redirect()->back()->with('error', 'Pengguna gagal dihapus !');
        }

        /* TODO: Delete Thumbnail, related file, etc */

        // Simpan logs aktivitas pengguna
        $logs = $user->name . ' telah menghapus pengguna dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->back()->with('message', 'Pengguna berhasil dihapus !');
    }

    public function blokirUser(Request $request): RedirectResponse
    {
        $blockedUser = User::findOrFail(Crypt::decrypt($request->id));
        if (!$blockedUser) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan !');
        }

        if ($blockedUser->role === UserRole::SUPER_ADMIN->value) {
            return redirect()->back()->with('error', 'Tidak dapat memblokir jenis pengguna ini !');
        }

        $user = Auth::user();
        $message = '';
        if ($blockedUser->blokir == 'Y') {
            $blockedUser->blokir = 'T';

            $logs = $user->name . ' telah membuka blokir pengguna dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            $message = 'Pengguna berhasil diunblokir';
        } else {
            $blockedUser->blokir = 'Y';

            $logs = $user->name . ' telah meblokir pengguna dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            $message = 'Pengguna berhasil diblokir !';
        }

        $save = $blockedUser->save();
        if (!$save) {
            return redirect()->route('user.main')->with('error', 'Pengguna gagal diubah !');
        }

        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('user.main')->with('message', $message);
    }
}
