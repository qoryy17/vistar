<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\ProgressUjian;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SimpanInformasiUjianJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $dataInfoUjian;

    /**
     * Create a new job instance.
     */
    public function __construct(array $dataInfoUjian)
    {
        $this->dataInfoUjian = $dataInfoUjian;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $ujian = Ujian::findOrFail($this->dataInfoUjian['ujianID']);
            $startTime = $ujian->waktu_mulai;
            $duration = $ujian->durasi_ujian; // Durasi dalam menit

            // Menghitung waktu selesai ujian
            $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('Y-m-d H:i:s');

            $now = Carbon::now(); // Waktu sekarang
            $targetTime = Carbon::create($endTime); // Waktu yang dituju
            $diffInMinutes = $now->diffInMinutes($targetTime);
            // Dapatkan sisa waktu ujian
            if ($now->greaterThan($targetTime)) {
                $sisaWaktu = 0;
            } else {
                $sisaWaktu = floor($diffInMinutes);
            }

            $ujian->waktu_berakhir = now();
            $ujian->sisa_waktu = $sisaWaktu;

            // Menghitung soal terjawab dan belum terjawab
            $hitungSoalTerjawab = ProgressUjian::where('ujian_id', $this->dataInfoUjian['ujianID'])->count();
            $hitungTotalSoal = SoalUjian::where('kode_soal', $this->dataInfoUjian['kodeSoal'])->count();

            $ujian->soal_terjawab = $hitungSoalTerjawab;
            $ujian->soal_belum_terjawab = $hitungTotalSoal - $hitungSoalTerjawab;
            $ujian->status_ujian = 'Selesai';
            $ujian->save();
        } catch (Exception $e) {
            // Jika terjadi kesalahan, job ini akan gagal dan otomatis masuk ke antrean ulang
            throw $e;
        }
    }

    // public function failed(Exception $exception)
    // {
    //     // Logika yang ingin dijalankan ketika job telah gagal meski sudah dicoba ulang
    //     // Misalnya: Mengirim notifikasi kepada admin atau mencatat ke dalam log
    // }
}
