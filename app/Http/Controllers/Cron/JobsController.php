<?php

namespace App\Http\Controllers\Cron;

use Carbon\Carbon;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JobsController extends Controller
{
    public function deleteLogs()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $deleteLogs = Logs::where('created_at', '<', $thirtyDaysAgo)->delete();

        if ($deleteLogs) {
            return response()->json(['status' => 'success delete jobs in 30 days ago']);
        }
    }

    public function deleteCache()
    {
        $deleteCache = DB::table('cache')->delete();
        if ($deleteCache) {
            return response()->json(['status' => 'success delete all cache in database']);
        }
    }
}
