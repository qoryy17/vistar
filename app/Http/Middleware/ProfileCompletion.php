<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->to('signin')->with('error', 'Silahkan Masuk terlebih dahulu!');
        }

        $user = Auth::user();
        if ($user->blokir == 'Y') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('mainweb.index')->with('error', 'Akun snda sedang terblokir!');
        }

        $customer = Customer::findOrFail($user->customer_id);
        if (
            is_null($customer->tanggal_lahir) ||
            is_null($customer->jenis_kelamin) ||
            is_null($customer->kontak) ||
            is_null($customer->alamat) ||
            is_null($customer->provinsi) ||
            is_null($customer->kabupaten) ||
            is_null($customer->kecamatan) ||
            is_null($customer->pendidikan) ||
            is_null($customer->jurusan) ||
            is_null($customer->foto)
        ) {
            return redirect()->route('mainweb.profile')->with('profilMessage', 'Harap lengkapi profil terlebih dahulu !!!');
        }

        return $next($request);
    }
}