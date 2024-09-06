<?php

namespace App\Jobs;

use Exception;
use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\ProgressUjian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SimpanJawabanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $jawabanPeserta;
    /**
     * Create a new job instance.
     */

    public function __construct(array $jawabanPeserta)
    {
        $this->jawabanPeserta = $jawabanPeserta;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $progressID = rand(1, 999) . rand(1, 99) . rand(1, 9);

            // Cek apakah jawaban sudah ada di database
            $existingJawaban = DB::table('progres_ujian')
                ->where('ujian_id', Crypt::decrypt($this->jawabanPeserta['ujian_id']))
                ->where('soal_ujian_id', Crypt::decrypt($this->jawabanPeserta['soal_ujian_id']))
                ->where('kode_soal', Crypt::decrypt($this->jawabanPeserta['kode_soal']))
                ->first();

            if ($existingJawaban) {
                // Update jawaban jika sudah ada
                DB::table('progres_ujian')
                    ->where('id', $existingJawaban->id)
                    ->update([
                        'jawaban' => $this->jawabanPeserta['jawaban'],
                        'updated_at' => now(),
                    ]);
            } else {
                // Simpan jawaban baru
                DB::table('progres_ujian')->insert([
                    'id' => $progressID,
                    'ujian_id' => Crypt::decrypt($this->jawabanPeserta['ujian_id']),
                    'soal_ujian_id' => Crypt::decrypt($this->jawabanPeserta['soal_ujian_id']),
                    'kode_soal' => Crypt::decrypt($this->jawabanPeserta['kode_soal']),
                    'jawaban' => $this->jawabanPeserta['jawaban'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $hitungSoalTerjawab = ProgressUjian::where('ujian_id', Crypt::decrypt($this->jawabanPeserta['ujian_id']))->count();
            $hitungTotalSoal = SoalUjian::where('kode_soal', Crypt::decrypt($this->jawabanPeserta['kode_soal']))->count();

            $ujian = Ujian::findOrFail(Crypt::decrypt($this->jawabanPeserta['ujian_id']));

            $ujian->soal_terjawab = $hitungSoalTerjawab;
            $ujian->soal_belum_terjawab = $hitungTotalSoal - $hitungSoalTerjawab;

            $ujian->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // public function failed(Exception $exception)
    // {
    //     // Logika yang ingin dijalankan ketika job telah gagal meski sudah dicoba ulang
    //     // Misalnya: Mengirim notifikasi kepada admin atau mencatat ke dalam log
    // }
}
