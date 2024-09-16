<?php

namespace App\Http\Controllers\Landing;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class GoogleOauth extends Controller
{
    public function redirectToGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        try {

            $finduser = User::where('google_id', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                $user = Auth::user();
                if ($user->role != 'Customer') {
                    return redirect()->intended('/');
                }
                // Jika customer alihkan kehalaman produk berbayar
                return redirect()->route('mainweb.product');
            }

            // check apakah sebelumnya sudah pernah mendaftar
            $emailRegister = User::where('email', htmlspecialchars($user->email))->first();
            if ($emailRegister) {
                if ($emailRegister->email == $user->email) {
                    // Tautkan ke google account
                    $emailRegister->google_id = $user->id;
                    $emailRegister->save();
                    return redirect()->route('mainweb.profil-saya')->with('profilMessage', 'Akun anda berhasil ditautkan ke Google !');
                } else {
                    return redirect()->route('mainweb.profil-saya')->with('errorMessage', 'Email Google tidak cocok dengan akun yang terdaftar !');
                }
            }

            // Generate id  otomatis
            $customerID = rand(1, 999) . rand(1, 99);
            $userID = rand(1, 999) . rand(1, 99);

            // Simpan data customer
            Customer::create([
                'id' => $customerID,
                'nama_lengkap' => $user->name,
            ]);

            $newUser = User::create([
                'id' => $userID,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'role' => 'Customer',
                'google_id' => $user->id,
                'customer_id' => $customerID,
                'kode_referral' => Str::random(5),
                'blokir' => 'T'
            ]);

            Auth::login($newUser);
            return redirect()->intended('/');
        } catch (\Throwable $th) {
            return Redirect::route('auth.signin')->with('info', 'Email akun anda tidak tertaut Google !');
        }
    }
}
