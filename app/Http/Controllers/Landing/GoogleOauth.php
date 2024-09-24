<?php

namespace App\Http\Controllers\Landing;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;


class GoogleOauth extends Controller
{
    public function redirectToGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $socialiteUser = Socialite::driver('google')->user();

        $currentLogin = Auth::user();
        if ($currentLogin) {
            return $this->handleLinkedAccount($socialiteUser, $currentLogin, $request);
        } else {
            return $this->handleSignInCallback($socialiteUser, $request);
        }
    }

    public function handleLinkedAccount(\Laravel\Socialite\Contracts\User $socialiteUser, User $currentLogin, Request $request)
    {
        if (!is_null($currentLogin->google_id)) {
            return redirect()->route('mainweb.profile')->with('error', 'Akun anda sudah terkoneksi ke Akun Google');
        }

        // Check if email not the same as current login
        if ($currentLogin->email !== $socialiteUser->email) {
            return redirect()->route('mainweb.profile')->with('error', 'Email Google anda tidak sesuai dengan akun ini');
        }

        $save = $currentLogin->update([
            'google_id' => $socialiteUser->id
        ]);
        if (!$save) {
            return redirect()->route('mainweb.profile')->with('error', 'Gagal menghubungkan Akun Google');
        }

        return redirect()->route('mainweb.profile')->with('success', 'Anda berhasil menghubungkan Akun Google, sekarang anda dapat login menggunakan Akun Google');
    }


    public function handleSignInCallback(\Laravel\Socialite\Contracts\User $socialiteUser, Request $request)
    {
        $user = User::where('google_id', $socialiteUser->id)->first();
        if ($user) {
            return Autentifikasi::signInProcess($user, $request->ip(), $request->userAgent());
        }

        try {
            DB::beginTransaction();

            // check apakah sebelumnya sudah pernah mendaftar
            $user = User::where('email', htmlspecialchars($socialiteUser->email))->first();
            if ($user) {
                // Tautkan ke google account
                $save = $user->update([
                    'google_id' => $socialiteUser->id
                ]);

                if (!$save) {
                    throw new Exception('Registrasi dengan Google gagal');
                }
            } else {
                $customer = Customer::create([
                    'nama_lengkap' => $socialiteUser->name,
                ]);
                if (!$customer) {
                    throw new Exception('Registrasi akun gagal');
                }

                $user = User::create([
                    'name' => $socialiteUser->name,
                    'email' => $socialiteUser->email,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'role' => 'Customer',
                    'google_id' => $socialiteUser->id,
                    'customer_id' => $customer->id,
                    'kode_referral' => Str::random(5),
                    'blokir' => 'T'
                ]);
            }

            if (!$user) {
                throw new Exception('Registrasi dengan Google gagal');
            }

            DB::commit();

            return Autentifikasi::signInProcess($user, $request->ip(), $request->userAgent());
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route('auth.signin')->with('error', $th->getMessage());
        }
    }
}
