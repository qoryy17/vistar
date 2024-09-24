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
            return redirect()->route('auth.signin', ['next-url' => $request->fullUrl()])->with('error', 'Silahkan Masuk terlebih dahulu!');
        }

        $user = Auth::user();
        if ($user->blokir == 'Y') {
            Auth::logout();

            return redirect()->route('mainweb.index')->with('error', 'Akun Anda sedang terblokir!');
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
            is_null($customer->jurusan)
        ) {
            return redirect()->route('mainweb.profile', ['next-url' => $request->fullUrl()])->with('profilMessage', 'Harap lengkapi informasi profil terlebih dahulu !!!');
        }
        if (is_null($customer->foto)) {
            return redirect()->route('mainweb.profile', ['next-url' => $request->fullUrl()])->with('profilMessage', 'Harap lengkapi photo profil terlebih dahulu !!!');
        }

        return $next($request);
    }
}
