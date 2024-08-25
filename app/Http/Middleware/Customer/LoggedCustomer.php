<?php

namespace App\Http\Middleware\Customer;

use Closure;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class LoggedCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return Redirect::to('/signin')->with('info', 'Silahkan login dulu !');
        } else {
            if (Auth::user()->blokir == 'Y') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return Redirect::to('/');
            }
            $user = Customer::findOrFail(Auth::user()->customer_id);
            if (
                is_null($user->tanggal_lahir) ||
                is_null($user->jenis_kelamin) ||
                is_null($user->kontak) ||
                is_null($user->alamat) ||
                is_null($user->provinsi) ||
                is_null($user->kabupaten) ||
                is_null($user->kecamatan) ||
                is_null($user->pendidikan) ||
                is_null($user->jurusan) ||
                is_null($user->foto)
            ) {
                return redirect()->route('mainweb.profil-saya')->with('profilMessage', 'Harap lengkapi profil terlebih dahulu sebelum melakukan pemesanan !!!');
            }
        }
        return $next($request);
    }
}
