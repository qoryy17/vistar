<?php

namespace App\Http\Middleware\Auth;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserCustomerMiddleware
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

        $allowedRoles = [UserRole::CUSTOMER->value];
        if (!in_array($user->role, $allowedRoles)) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengakses Halaman ini!');
        }

        return $next($request);
    }
}
