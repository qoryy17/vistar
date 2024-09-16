<?php

namespace App\Http\Controllers\Landing;

use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password as PasswordReset;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class Autentifikasi extends Controller
{
    public function signIn()
    {
        $data = ['web' => \App\Helpers\BerandaUI::web()];
        return view('main-web.autentifikasi.signin', $data);
    }

    public function signUp()
    {
        $data = ['web' => \App\Helpers\BerandaUI::web()];
        return view('main-web.autentifikasi.signup', $data);
    }

    public function authSignIn(LoginRequest $request): RedirectResponse
    {
        // Validasi inputan
        $request->validated();
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            // Generate session ulang
            $request->session()->regenerate();
            $user = Auth::user();

            // Jika user bukan customer alihkan kehalaman panel kendali khusus superadmin/admin/finance/tentor
            if ($user->role != 'Customer') {
                // Simpan log aktivitas pengguna
                $logs = $user->name . ' telah login aplikasi, waktu tercatat : ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                session(['user' => $user]);
                return redirect()->intended('/main/dashboard');
            }
            // Jika bukan alihkan kehalaman produk berbayar
            return redirect()->route('mainweb.product');
        }
        return back()->with('error', 'Username/Password salah !')->withInput();
    }

    public function authSignOut(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }

    public function registerUser(Request $request): RedirectResponse
    {
        // Validasi inputan
        $request->validate([
            'namaLengkap' => ['required', 'string'],
            'email' => ['required', 'email', 'string', 'lowercase', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'string',
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*?&]/', // must contain a special character
                Password::defaults(),

            ],
        ], [
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password harus mengandung 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
            'password.confirmed' => 'Password konfirmasi tidak cocok',
        ]);

        $customerID = rand(1, 999) . rand(1, 99);
        $userID = rand(1, 999) . rand(1, 99);

        $customer = new Customer();
        $customer->id = $customerID;
        $customer->nama_lengkap = htmlspecialchars($request->input('namaLengkap'));

        $user = new User();
        $user->id = $userID;
        $user->name = htmlspecialchars($request->input('namaLengkap'));
        $user->email = htmlspecialchars($request->input('email'));
        $user->password = Hash::make($request->input('password'));
        $user->remember_token = Str::random(10);
        $user->role = 'Customer';
        $user->customer_id = $customerID;
        $user->kode_referral = Str::random(5);
        $user->blokir = 'Y';

        if ($customer->save() and $user->save()) {

            // event(new Registered($user));
            // Send email registration to customer with email
            $this->sendVerificationEmail($user);

            return redirect()->route('auth.signup')->with('message', 'Silahkan konfirmasi akun melalui email !');
        } else {
            return back()->with('error', 'Registrasi akun gagal !')->withInput();
        }
    }

    protected function sendVerificationEmail($user)
    {
        $verificationUrl = $this->generateVerificationUrl($user);

        Mail::send('main-web.autentifikasi.verifikasi-email', ['url' => $verificationUrl, 'user' => $user], function ($message) use ($user) {
            $message->to($user['email']);
            $message->subject('Konfirmasi Email');
        });
    }

    protected function generateVerificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user['id'], 'hash' => sha1($user['email'])]
        );
    }

    public function resetPassword()
    {
        return view('main-web.autentifikasi.send-link-email');
    }

    public function sendLinkEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = PasswordReset::sendResetLink(
            $request->only('email'),
            function (User $user, string $token) {
                $url = url(route('auth.password-reset', [
                    'token' => $token,
                    'email' => $user->email,
                ], false));

                Mail::send('main-web.autentifikasi.verifikasi-reset-password', ['url' => $url, 'user' => $user], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Reset Password');
                });
            }
        );

        return $status === PasswordReset::RESET_LINK_SENT
        ? back()->with(['status' => __($status), 'message' => 'Link berhasil dikirim ke email !'])
        : back()->withErrors(['email' => __($status)]);
    }

    public function formResetPassword(Request $request, $token = null)
    {
        return view('main-web.autentifikasi.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function simpanPasswordReset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'string',
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*?&]/', // must contain a special character
                Password::defaults(),

            ],
        ], [
            'password.min' => 'Password harus mengandung 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kapital, angka dan karakter',
            'password.confirmed' => 'Password konfirmasi tidak cocok',
        ]);

        $status = PasswordReset::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                Auth::login($user);
            }
        );

        return $status === PasswordReset::PASSWORD_RESET
        ? redirect()->route('mainweb.index')->with('status', __($status))
        : back()->with('error', 'Formulir tidak berlaku silahkan kirim ulang email !')->withErrors(['email' => [__($status)]]);
    }
}
