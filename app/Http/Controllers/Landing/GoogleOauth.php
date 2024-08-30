<?php

namespace App\Http\Controllers\Landing;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
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
        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                Auth::user();
                return redirect()->intended('/');
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
            return Redirect::to('/');
        }
    }
}
