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
        }
        return $next($request);
    }
}
