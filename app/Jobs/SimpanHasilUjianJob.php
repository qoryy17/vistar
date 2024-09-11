<?php

namespace App\Jobs;

use App\Models\HasilPassingGrade;
use App\Models\HasilUjian;
use App\Models\KlasifikasiSoal;
use App\Models\ProgressUjian;
use App\Models\SoalUjian;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SimpanHasilUjianJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $examInfo;

    /**
     * Create a new job instance.
     */
    public function __construct(array $examInfo)
    {
        $this->examInfo = $examInfo;

    }

    /**
     * Process Data
     */
    public function processData($examId, $questionCode)
    {
        $answered = ProgressUjian::where('kode_soal', $questionCode)
            ->where('ujian_id', $examId)
            ->with('soal', function ($query) {
                $query->select('id', 'kode_soal', 'poin_a', 'poin_b', 'poin_c', 'poin_d', 'poin_e', 'berbobot', 'kunci_jawaban', 'klasifikasi_soal_id');
            })
            ->get();
        $totalAnswered = $answered->count();
        $totalQuestions = SoalUjian::where('kode_soal', $questionCode)->count();

        $totalPerClassification = [];

        foreach ($answered as $answer) {
            $soal = $answer->soal;
            if ($soal === null) {
                continue;
            }

            $classificationId = $soal->klasifikasi_soal_id;
            if ($classificationId === null) {
                continue;
            }

            $score = 0;
            $right = 0;
            $wrong = 0;
            $soalArray = $soal->toArray();
            if (strval($soal->berbobot) !== '1') {
                if ($answer->jawaban === $soal->kunci_jawaban) {
                    $right = 1;
                } else {
                    $wrong = 1;
                }
            }
            $score = @$soalArray['poin_' . strtolower($answer->jawaban)] ?? 0;

            if (array_key_exists($classificationId, $totalPerClassification)) {
                $totalPerClassification[$classificationId]['total_score'] = $totalPerClassification[$classificationId]['total_score'] + $score;
                $totalPerClassification[$classificationId]['right'] = $totalPerClassification[$classificationId]['right'] + $right;
                $totalPerClassification[$classificationId]['wrong'] = $totalPerClassification[$classificationId]['wrong'] + $wrong;
            } else {
                $totalPerClassification[$classificationId] = [
                    'total_score' => $score,
                    'right' => $right,
                    'wrong' => $wrong,
                ];
            }
        }

        return [
            'total_questions' => $totalQuestions,
            'total_answered' => $totalAnswered,
            'total_per_classification' => $totalPerClassification,
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $examInfo = $this->examInfo;

        try {
            $examId = $examInfo['examId'];
            $questionCode = $examInfo['questionCode'];

            // Delete if there is previous exam result
            HasilUjian::where('ujian_id', $examId)->delete();

            $processData = $this->processData($examId, $questionCode);
            $totalPerClassification = $processData['total_per_classification'];
            $totalQuestions = $processData['total_questions'];
            $totalAnswered = $processData['total_answered'];

            // Get All Classification on this Exam
            $classificationIds = SoalUjian::where('kode_soal', $questionCode)
                ->select('klasifikasi_soal_id')
                ->groupBy('klasifikasi_soal_id')
                ->get()
                ->pluck('klasifikasi_soal_id')
                ->toArray();

            $keterangan = 'Lulus';

            $savedClassification = [];

            $totalScore = 0;
            $totalRight = 0;
            $totalWrong = 0;
            foreach ($classificationIds as $classificationId) {
                $classification = KlasifikasiSoal::find($classificationId);
                if (!$classification) {
                    continue;
                }

                $totalScorePerClassification = 0;
                $totalRightPerClassification = 0;
                $totalWrongPerClassification = 0;
                if (array_key_exists($classificationId, $totalPerClassification)) {
                    $classificationResult = $totalPerClassification[$classificationId];
                    $totalScorePerClassification = $classificationResult['total_score'];
                    $totalRightPerClassification = $classificationResult['wrong'];
                    $totalWrongPerClassification = $classificationResult['right'];

                }

                if ($totalScorePerClassification < $classification->passing_grade) {
                    $keterangan = 'Gagal';
                }

                $totalScore += $totalScorePerClassification;
                $totalRight += $totalWrongPerClassification;
                $totalWrong += $totalRightPerClassification;

                array_push($savedClassification, [
                    'judul' => $classification->judul,
                    'alias' => $classification->alias,
                    'passing_grade' => $classification->passing_grade,
                    'total_nilai' => $totalScorePerClassification,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $examResult = HasilUjian::create([
                'ujian_id' => $examId,
                'durasi_selesai' => now(),
                'benar' => $totalRight,
                'salah' => $totalWrong,
                'terjawab' => $totalAnswered,
                'tidak_terjawab' => $totalQuestions - $totalAnswered,
                'total_nilai' => $totalScore,
                'keterangan' => $keterangan,
            ]);

            if (!$examResult) {
                throw new Exception('Gagal menyimpan Hasil Ujian.');
            }

            // Add exam result id on result passing grade data
            foreach ($savedClassification as $key => $classification) {
                $savedClassification[$key]['hasil_ujian_id'] = $examResult->id;
            }

            HasilPassingGrade::insert($savedClassification);
        } catch (Exception $exception) {
            Log::channel('exam_report')->error($exception->getMessage(),
                [
                    'jobId' => $this->job->getJobId(),
                    'examInfo' => $this->examInfo,
                ]
            );
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::channel('exam_report')->error($exception->getMessage(),
            [
                'examInfo' => $this->examInfo,
                'trace' => $exception,
            ]
        );
    }
}
