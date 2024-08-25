<?php

namespace App\Http\Controllers\Landing;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;

class Emails extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('auth.signin')->with('message', 'Email sudah dikonfirmasi !');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            $user->blokir = 'T';
            $user->save();
        }

        return redirect()->route('auth.signin')->with('message', 'Akun anda telah aktif !');
    }
}
