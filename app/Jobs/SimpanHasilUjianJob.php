<?php

namespace App\Jobs;

use Exception;
use App\Models\SoalUjian;
use App\Models\HasilUjian;
use App\Models\ProgressUjian;
use App\Models\KlasifikasiSoal;
use App\Models\HasilPassingGrade;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SimpanHasilUjianJob implements ShouldQueue
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

            if ($this->dataInfoUjian['param'] == 'berbayar') {
                // Menyimpan Hasil AKhir ujian Berbayar
                $ujian = DB::table('ujian')->select('ujian.*', 'order_tryout.produk_tryout_id')->leftJoin('order_tryout', 'ujian.order_tryout_id', '=', 'order_tryout.id')->where('ujian.id', '=', $this->dataInfoUjian['ujianID'])->first();
            } else {
                // Menyimpan Hasil AKhir ujian Gratis
                $ujian = DB::table('ujian')->select('ujian.*', 'limit_tryout.produk_tryout_id')->leftJoin('limit_tryout', 'ujian.limit_tryout_id', '=', 'limit_tryout.id')->where('ujian.id', '=', $this->dataInfoUjian['ujianID'])->first();
            }
            $cariStatusProduk = DB::table('produk_tryout')->select('produk_tryout.kategori_produk_id', 'kategori_produk.status')->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')->where('produk_tryout.id', '=', $ujian->produk_tryout_id)->first();
            $cariPassingGrade = DB::table('produk_tryout')->select('produk_tryout.pengaturan_tryout_id', 'pengaturan_tryout.passing_grade')->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')->where('produk_tryout.id', '=', $ujian->produk_tryout_id)->first();

            $jawabanUjian = DB::table('progres_ujian')->select('progres_ujian.*', 'ujian.status_ujian')
                ->leftJoin('ujian', 'progres_ujian.ujian_id', '=', 'ujian.id')
                ->where('progres_ujian.ujian_id', '=', $this->dataInfoUjian['ujianID'])->get();

            $totalSoalTersedia = SoalUjian::where('kode_soal', $this->dataInfoUjian['kodeSoal'])->count();
            $totalSoalTerisi = ProgressUjian::where('kode_soal', $this->dataInfoUjian['kodeSoal'])->where('ujian_id', $this->dataInfoUjian['ujianID'])->count();

            $statusProduk = $cariStatusProduk->status;
            $passingGrade = $cariPassingGrade->passing_grade;
            $totalBenar = [];
            $totalSalah = [];
            $totalTWK = [];
            $totalTIU = [];
            $totalTKP = [];
            $hasilScoreUjian = 0;
            $jawabanSalah = [];

            if ($statusProduk == 'Gratis') {
                // Hitung score ujian berdasarkan kunci jawaban soal
                foreach ($jawabanUjian as $jawabanPeserta) {
                    $soalUjian = SoalUjian::find($jawabanPeserta->soal_ujian_id);
                    $klasifikasiSoal = KlasifikasiSoal::find($soalUjian->klasifikasi_soal_id);
                    $poinMap = [
                        'A' => $soalUjian->poin_a,
                        'B' => $soalUjian->poin_b,
                        'C' => $soalUjian->poin_c,
                        'D' => $soalUjian->poin_d,
                        'E' => $soalUjian->poin_e,
                    ];

                    if ($klasifikasiSoal->alias == 'TWK') {
                        $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                        if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                            $totalBenar[] = 1;
                        } else {
                            $totalSalah[] = 1;
                            $poinSoal = 0;
                            $jawabanSalah[] = [
                                'jawabanSalahID' => $jawabanPeserta->id,
                                'jawabanSalah' => $jawabanPeserta->jawaban,
                                'kunciJawaban' => $soalUjian->kunci_jawaban
                            ];
                        }
                        $pointTWK[] = $poinSoal;
                        $dataGradeTWK = [
                            'judul' => $klasifikasiSoal->judul,
                            'alias' => $klasifikasiSoal->alias,
                            'passingGrade' => $klasifikasiSoal->passing_grade
                        ];
                    } else {
                        $passingTWK = KlasifikasiSoal::where('alias', 'TWK')->first();
                        $dataGradeTWK = [
                            'judul' => 'Tes Wawasan Kebangsaan',
                            'alias' => 'TWK',
                            'passingGrade' => $passingTWK->passing_grade
                        ];
                    }

                    if ($klasifikasiSoal->alias == 'TIU') {
                        $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                        if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                            $totalBenar[] = 1;
                        } else {
                            $totalSalah[] = 1;
                            $poinSoal = 0;
                            $jawabanSalah[] = [
                                'jawabanSalahID' => $jawabanPeserta->id,
                                'jawabanSalah' => $jawabanPeserta->jawaban,
                                'kunciJawaban' => $soalUjian->kunci_jawaban
                            ];
                        }
                        $totalTIU[] = $poinSoal;
                        $dataGradeTIU = [
                            'judul' => $klasifikasiSoal->judul,
                            'alias' => $klasifikasiSoal->alias,
                            'passingGrade' => $klasifikasiSoal->passing_grade
                        ];
                    } else {
                        $passingTIU = KlasifikasiSoal::where('alias', 'TIU')->first();
                        $dataGradeTIU = [
                            'judul' => 'Tes Intelegensi Umum',
                            'alias' => 'TIU',
                            'passingGrade' => $passingTIU->passing_grade
                        ];
                    }

                    if ($klasifikasiSoal->alias == 'TKP') {
                        $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                        if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                            $totalBenar[] = 1;
                        } else {
                            $totalSalah[] = 1;
                            $jawabanSalah[] = [
                                'jawabanSalahID' => $jawabanPeserta->id,
                                'jawabanSalah' => $jawabanPeserta->jawaban,
                                'kunciJawaban' => $soalUjian->kunci_jawaban
                            ];
                        }
                        $totalTKP[] = $poinSoal;
                        $dataGradeTKP = [
                            'judul' => $klasifikasiSoal->judul,
                            'alias' => $klasifikasiSoal->alias,
                            'passingGrade' => $klasifikasiSoal->passing_grade
                        ];
                    } else {
                        $passingTKP = KlasifikasiSoal::where('alias', 'TKP')->first();
                        $dataGradeTKP = [
                            'judul' => 'Tes Karakteristik Pribadi',
                            'alias' => 'TKP',
                            'passingGrade' => $passingTKP->passing_grade
                        ];
                    }
                }

                // Akumulasikan total nilai TWK, TIU dan TKP
                if (isset($pointTWK)) {
                    $totalTWK[] = array_sum($pointTWK);
                    $totalNilaiTWK = array_sum($totalTWK);
                } else {
                    $totalNilaiTWK = 0;
                }

                if (isset($pointTIU)) {
                    $totalTIU[] = array_sum($pointTIU);
                    $totalNilaiTIU = array_sum($totalTIU);
                } else {
                    $totalNilaiTIU = 0;
                }

                if (isset($pointTKP)) {
                    $totalTKP[] = array_sum($pointTKP);
                    $totalNilaiTKP = array_sum($totalTKP);
                } else {
                    $totalNilaiTKP = 0;
                }

                $hasilScoreUjian = $totalNilaiTWK + $totalNilaiTIU + $totalNilaiTKP;

                // Cek apakah pengisian soal sudah mencapat 70% dari total soal
                $ambangBatasPengisian = ceil($totalSoalTersedia * 0.7);
                if ($totalSoalTerisi >= $ambangBatasPengisian) {
                    // Cek apakah score dibawah passing grade
                    if ($totalNilaiTWK < $dataGradeTWK['passingGrade']) {
                        // Jika ga lulus passing grade, buat menjadi lulus
                        foreach ($jawabanSalah as $ubahJawabanSalah) {
                            // Lakukan update jawaban salah ke jawaban benar
                            ProgressUjian::where('id', $ubahJawabanSalah['jawabanSalahID'])->update(
                                ['jawaban' => $ubahJawabanSalah['kunciJawaban']]
                            );
                        }
                    } elseif ($totalNilaiTIU < $dataGradeTIU['passingGrade']) {
                        // Jika ga lulus passing grade, buat menjadi lulus
                        foreach ($jawabanSalah as $ubahJawabanSalah) {
                            // Lakukan update jawaban salah ke jawaban benar
                            ProgressUjian::where('id', $ubahJawabanSalah['jawabanSalahID'])->update(
                                ['jawaban' => $ubahJawabanSalah['kunciJawaban']]
                            );
                        }
                    } elseif ($totalNilaiTKP < $dataGradeTKP['passingGrade']) {
                        // Jika ga lulus passing grade, buat menjadi lulus
                        foreach ($jawabanSalah as $ubahJawabanSalah) {
                            // Lakukan update jawaban salah ke jawaban benar
                            ProgressUjian::where('id', $ubahJawabanSalah['jawabanSalahID'])->update(
                                ['jawaban' => $ubahJawabanSalah['kunciJawaban']]
                            );
                        }
                    }
                } else {
                    // Hitung score ujian berdasarkan kunci jawaban soal
                    foreach ($jawabanUjian as $jawabanPeserta) {
                        $soalUjian = SoalUjian::find($jawabanPeserta->soal_ujian_id);
                        $klasifikasiSoal = KlasifikasiSoal::find($soalUjian->klasifikasi_soal_id);

                        $poinMap = [
                            'A' => $soalUjian->poin_a,
                            'B' => $soalUjian->poin_b,
                            'C' => $soalUjian->poin_c,
                            'D' => $soalUjian->poin_d,
                            'E' => $soalUjian->poin_e,
                        ];

                        if ($klasifikasiSoal->alias == 'TWK') {
                            $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                            if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                                $totalBenar[] = 1;
                            } else {
                                $totalSalah[] = 1;
                                $poinSoal = 0;
                            }
                            $pointTWK[] = $poinSoal;
                            $dataGradeTWK = [
                                'judul' => $klasifikasiSoal->judul,
                                'alias' => $klasifikasiSoal->alias,
                                'passingGrade' => $klasifikasiSoal->passing_grade
                            ];
                        } else {
                            $passingTWK = KlasifikasiSoal::where('alias', 'TWK')->first();
                            $dataGradeTWK = [
                                'judul' => 'Tes Wawasan Kebangsaan',
                                'alias' => 'TWK',
                                'passingGrade' => $passingTWK->passing_grade
                            ];
                        }

                        if ($klasifikasiSoal->alias == 'TIU') {
                            $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                            if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                                $totalBenar[] = 1;
                            } else {
                                $totalSalah[] = 1;
                                $poinSoal = 0;
                            }
                            $pointTIU[] = $poinSoal;
                            $dataGradeTIU = [
                                'judul' => $klasifikasiSoal->judul,
                                'alias' => $klasifikasiSoal->alias,
                                'passingGrade' => $klasifikasiSoal->passing_grade
                            ];
                        } else {
                            $passingTIU = KlasifikasiSoal::where('alias', 'TIU')->first();
                            $dataGradeTIU = [
                                'judul' => 'Tes Intelegensi Umum',
                                'alias' => 'TIU',
                                'passingGrade' => $passingTIU->passing_grade
                            ];
                        }

                        if ($klasifikasiSoal->alias == 'TKP') {
                            $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                            if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                                $totalBenar[] = 1;
                            } else {
                                $totalSalah[] = 1;
                            }
                            $pointTKP[] = $poinSoal;
                            $dataGradeTKP = [
                                'judul' => $klasifikasiSoal->judul,
                                'alias' => $klasifikasiSoal->alias,
                                'passingGrade' => $klasifikasiSoal->passing_grade
                            ];
                        } else {
                            $passingTKP = KlasifikasiSoal::where('alias', 'TKP')->first();
                            $dataGradeTKP = [
                                'judul' => 'Tes Karakteristik Pribadi',
                                'alias' => 'TKP',
                                'passingGrade' => $passingTKP->passing_grade
                            ];
                        }
                    }

                    // Akumulasikan total nilai TWK, TIU dan TKP
                    if (isset($pointTWK)) {
                        $totalTWK[] = array_sum($pointTWK);
                        $totalNilaiTWK = array_sum($totalTWK);
                    } else {
                        $totalNilaiTWK = 0;
                    }

                    if (isset($pointTIU)) {
                        $totalTIU[] = array_sum($pointTIU);
                        $totalNilaiTIU = array_sum($totalTIU);
                    } else {
                        $totalNilaiTIU = 0;
                    }

                    if (isset($pointTKP)) {
                        $totalTKP[] = array_sum($pointTKP);
                        $totalNilaiTKP = array_sum($totalTKP);
                    } else {
                        $totalNilaiTKP = 0;
                    }

                    $hasilScoreUjian = $totalNilaiTWK + $totalNilaiTIU + $totalNilaiTKP;
                }
            } else {
                // Hitung score ujian berdasarkan kunci jawaban soal
                foreach ($jawabanUjian as $jawabanPeserta) {
                    $soalUjian = SoalUjian::find($jawabanPeserta->soal_ujian_id);
                    $klasifikasiSoal = KlasifikasiSoal::find($soalUjian->klasifikasi_soal_id);
                    $poinMap = [
                        'A' => $soalUjian->poin_a,
                        'B' => $soalUjian->poin_b,
                        'C' => $soalUjian->poin_c,
                        'D' => $soalUjian->poin_d,
                        'E' => $soalUjian->poin_e,
                    ];

                    if ($klasifikasiSoal->alias == 'TWK') {
                        $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                        if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                            $totalBenar[] = 1;
                        } else {
                            $totalSalah[] = 1;
                            $poinSoal = 0;
                        }
                        $pointTWK[] = $poinSoal;
                        $dataGradeTWK = [
                            'judul' => $klasifikasiSoal->judul,
                            'alias' => $klasifikasiSoal->alias,
                            'passingGrade' => $klasifikasiSoal->passing_grade
                        ];
                    } else {
                        $passingTWK = KlasifikasiSoal::where('alias', 'TWK')->first();
                        $dataGradeTWK = [
                            'judul' => 'Tes Wawasan Kebangsaan',
                            'alias' => 'TWK',
                            'passingGrade' => $passingTWK->passing_grade
                        ];
                    }

                    if ($klasifikasiSoal->alias == 'TIU') {
                        $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                        if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                            $totalBenar[] = 1;
                        } else {
                            $totalSalah[] = 1;
                            $poinSoal = 0;
                        }
                        $pointTIU[] = $poinSoal;
                        $dataGradeTIU = [
                            'judul' => $klasifikasiSoal->judul,
                            'alias' => $klasifikasiSoal->alias,
                            'passingGrade' => $klasifikasiSoal->passing_grade
                        ];
                    } else {
                        $passingTIU = KlasifikasiSoal::where('alias', 'TIU')->first();
                        $dataGradeTIU = [
                            'judul' => 'Tes Intelegensi Umum',
                            'alias' => 'TIU',
                            'passingGrade' => $passingTIU->passing_grade
                        ];
                    }

                    if ($klasifikasiSoal->alias == 'TKP') {
                        $poinSoal = $poinMap[$jawabanPeserta->jawaban] ?? 0;
                        if ($jawabanPeserta->jawaban == $soalUjian->kunci_jawaban) {
                            $totalBenar[] = 1;
                        } else {
                            $totalSalah[] = 1;
                        }
                        $pointTKP[] = $poinSoal;
                        $dataGradeTKP = [
                            'judul' => $klasifikasiSoal->judul,
                            'alias' => $klasifikasiSoal->alias,
                            'passingGrade' => $klasifikasiSoal->passing_grade
                        ];
                    } else {
                        $passingTKP = KlasifikasiSoal::where('alias', 'TKP')->first();
                        $dataGradeTKP = [
                            'judul' => 'Tes Karakteristik Pribadi',
                            'alias' => 'TKP',
                            'passingGrade' => $passingTKP->passing_grade
                        ];
                    }
                }

                // Akumulasikan total nilai TWK, TIU dan TKP
                if (isset($pointTWK)) {
                    $totalTWK[] = array_sum($pointTWK);
                    $totalNilaiTWK = array_sum($totalTWK);
                } else {
                    $totalNilaiTWK = 0;
                }

                if (isset($pointTIU)) {
                    $totalTIU[] = array_sum($pointTIU);
                    $totalNilaiTIU = array_sum($totalTIU);
                } else {
                    $totalNilaiTIU = 0;
                }

                if (isset($pointTKP)) {
                    $totalTKP[] = array_sum($pointTKP);
                    $totalNilaiTKP = array_sum($totalTKP);
                } else {
                    $totalNilaiTKP = 0;
                }

                $hasilScoreUjian = $totalNilaiTWK + $totalNilaiTIU + $totalNilaiTKP;
            }

            if ($totalNilaiTWK < $dataGradeTWK['passingGrade']) {
                $keterangan = 'Gagal';
            } elseif (
                $totalNilaiTIU < $dataGradeTIU['passingGrade']
            ) {
                $keterangan = 'Gagal';
            } elseif ($totalNilaiTKP < $dataGradeTKP['passingGrade']) {
                $keterangan = 'Gagal';
            } else {
                $keterangan = 'Lulus';
            }

            $hasilUjianID = rand(1, 999) . rand(1, 99);

            $hasilPassingGradeSKD = [
                [
                    'id' => rand(1, 999) . rand(1, 99),
                    'hasil_ujian_id' => $hasilUjianID,
                    'judul' => $dataGradeTWK['judul'],
                    'alias' => $dataGradeTWK['alias'],
                    'passing_grade' => $dataGradeTWK['passingGrade'],
                    'total_nilai' => $totalNilaiTWK,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => rand(1, 999) . rand(1, 99),
                    'hasil_ujian_id' => $hasilUjianID,
                    'judul' => $dataGradeTIU['judul'],
                    'alias' => $dataGradeTIU['alias'],
                    'passing_grade' => $dataGradeTIU['passingGrade'],
                    'total_nilai' => $totalNilaiTIU,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => rand(1, 999) . rand(1, 99),
                    'hasil_ujian_id' => $hasilUjianID,
                    'judul' => $dataGradeTKP['judul'],
                    'alias' => $dataGradeTKP['alias'],
                    'passing_grade' => $dataGradeTKP['passingGrade'],
                    'total_nilai' => $totalNilaiTKP,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'benar' =>  array_sum($totalBenar),
                    'benar' => array_sum($totalSalah),
                    'terisi' => $totalSoalTerisi,
                    'terjawab' => $totalSoalTersedia - $totalSoalTerisi,
                    'score' => $hasilScoreUjian,
                    'keterangan' => $keterangan
                ]
            ];

            $hasilUjian = new HasilUjian();

            $hasilUjian->id = $hasilUjianID;
            $hasilUjian->ujian_id = $this->dataInfoUjian['ujianID'];
            $hasilUjian->durasi_selesai = now();
            $hasilUjian->benar = array_sum($totalBenar);
            $hasilUjian->salah = array_sum($totalSalah);
            $hasilUjian->terjawab = $totalSoalTerisi;
            $hasilUjian->tidak_terjawab = $totalSoalTersedia - $totalSoalTerisi;
            $hasilUjian->total_nilai = $hasilScoreUjian;
            $hasilUjian->keterangan = $keterangan;

            $hasilUjian->save();

            // Simpan hasil passing grade SKD
            HasilPassingGrade::insert($hasilPassingGradeSKD);
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
