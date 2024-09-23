<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        if (in_array($role, [UserRole::CUSTOMER->value])) {
            return redirect()->route('site.main');
        } elseif (in_array($role, [UserRole::MITRA->value])) {
            return redirect()->route('mitra.dashboard');
        } elseif (in_array($role, [UserRole::SUPER_ADMIN->value])) {
            return redirect()->route('main.dashboard');
        }

        return redirect()->route('mainweb.index');
    }
}
