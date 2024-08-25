<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus logs aktivitas pengguna setiap bulan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Hapus logs aktivitas yang lebih dari 1 bulan
        DB::table('activity_logs')
            ->where('created_at', '<', now()->subMonth())
            ->delete();

        $this->info('Logs aktivitas berhasil dihapus !');
    }
}
