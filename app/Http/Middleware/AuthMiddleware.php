<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
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

        if (Auth::user()->blokir == 'Y') {
            Auth::logout();

            return redirect()->route('mainweb.index')->with('error', 'Akun Anda sedang terblokir!');
        }

        return $next($request);
    }
}
