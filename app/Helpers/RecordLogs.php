<?php

namespace App\Helpers;

use App\Models\Logs;
use Illuminate\Support\Facades\Auth;

class RecordLogs
{
    public static function saveRecordLogs($ip = null, $userAgent = null, $logs = null)
    {
        // Simpan logs aktivitas pengguna
        $user = Auth::user();
        $aktivitas = new Logs();
        $aktivitas->user_id = $user->id;
        $aktivitas->ip_address = $ip;
        $aktivitas->user_agent = $userAgent;
        $aktivitas->aktivitas = $logs;
        $aktivitas->save();
    }
}
