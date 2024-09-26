<?php

namespace App\Http\Controllers\Landing;

use App\Enums\UserRole;
use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use App\Models\OrderTryout;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password as PasswordReset;
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
        $request->validated();
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $user = User::where('email', $credentials['email'])
            ->first();
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan!')->withInput();
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Password yang anda masukkan salah!')->withInput();
        }

        return Autentifikasi::signInProcess($user);
    }

    public static function signInProcess(User $user)
    {
        Auth::login($user, true);

        // Simpan log aktivitas pengguna
        $logs = $user->name . ' telah login aplikasi, waktu tercatat : ' . now();
        RecordLogs::saveRecordLogs(request()->ip(), request()->userAgent(), $logs);

        $nextUrl = request()->get('next-url');
        if ($nextUrl && filter_var($nextUrl, FILTER_VALIDATE_URL) && \App\Helpers\Common::isSameDomainFromURL(request()->getHttpHost(), $nextUrl)) {
            return redirect()->to($nextUrl);
        }

        $redirectRoute = 'user.dashboard';
        // redirect user to product page if user never purchase product
        if ($user->role === UserRole::CUSTOMER->value) {
            $orderExists = OrderTryout::select('id')
                ->where('customer_id', Auth::user()->customer_id)
                ->where('status_order', 'paid')
                ->first();
            if (!$orderExists) {
                $redirectRoute = 'mainweb.product';
            }
        }

        return redirect()->route($redirectRoute);
    }

    public function authSignOut(Request $request): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('mainweb.index');
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

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'nama_lengkap' => htmlspecialchars($request->input('namaLengkap')),
            ]);
            if (!$customer) {
                throw new Exception('Registrasi akun gagal');
            }

            $user = User::create([
                'name' => htmlspecialchars($request->input('namaLengkap')),
                'email' => htmlspecialchars($request->input('email')),
                'password' => Hash::make($request->input('password')),
                'remember_token' => Str::random(10),
                'role' => 'Customer',
                'customer_id' => $customer->id,
                'kode_referral' => Str::random(5),
                'blokir' => 'Y',
            ]);

            if (!$user) {
                throw new Exception('Registrasi akun gagal');
            }

            // Send email registration to customer with email
            $this->sendVerificationEmail($user);

            DB::commit();

            return redirect()->route('auth.signin', ['next-url' => request()->get('next-url')])->with('message', 'Silahkan konfirmasi akun melalui email !');
        } catch (\Throwable $th) {
            DB::rollback();

            return back()->with('error', $th->getMessage())->withInput();
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
