<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\TestimoniRequest;
use App\Jobs\SimpanHasilUjianJob;
use App\Models\Customer;
use App\Models\HasilPassingGrade;
use App\Models\HasilUjian;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use App\Models\ProdukTryout;
use App\Models\ProgressUjian;
use App\Models\SoalUjian;
use App\Models\Testimoni;
use App\Models\Ujian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class Tryoutc extends Controller
{

    public static function cacheNameGenerateExam(string $id): array
    {
        return [
            'exam' => 'exam:' . $id . ':data',
            'question' => 'exam:' . $id . ':questions',
        ];
    }

    public function berandaUjian(Request $request): RedirectResponse
    {
        // Decrypt ID
        $id = $request->id;
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ujian Tidak ditemukan!');
        }

        // Decrypt Param
        $param = $request->param;
        try {
            $param = Crypt::decrypt($param);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ujian Tidak diketahui!');
        }

        $newExamData = [];

        if ($param == 'berbayar') {
            // Jika ada proses ujian yang sedang berlangsung arahkan kehalaman ujian
            $cekWaitingExam = Ujian::select('id')
                ->where('order_tryout_id', $id)
                ->where('status_ujian', 'Sedang Dikerjakan')
                ->first();
            if ($cekWaitingExam) {
                return redirect()->route('ujian.progress', ['id' => Crypt::encrypt($cekWaitingExam->id), 'param' => $request->param]);
            }

            $ujian = DB::table('order_tryout')
                ->select('order_tryout.id', 'produk_tryout.id as produk_id', 'pengaturan_tryout.durasi')
                ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->where('order_tryout.id', '=', $id)
                ->first();

            $newExamData['order_tryout_id'] = $id;
        } elseif ($param == 'gratis') {
            // Jika ada proses ujian yang sedang berlangsung arahkan kehalaman ujian
            $cekWaitingExam = Ujian::select('id')
                ->where('limit_tryout_id', $id)
                ->where('status_ujian', 'Sedang Dikerjakan')
                ->first();
            if ($cekWaitingExam) {
                return redirect()->route('ujian.progress', ['id' => Crypt::encrypt($cekWaitingExam->id), 'param' => $request->param]);
            }

            $ujian = DB::table('limit_tryout')->select('limit_tryout.id', 'produk_tryout.id as produk_id', 'pengaturan_tryout.durasi')
                ->leftJoin('produk_tryout', 'limit_tryout.produk_tryout_id', '=', 'produk_tryout.id')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->where('limit_tryout.id', '=', $id)
                ->first();

            $newExamData['limit_tryout_id'] = $id;
        } else {
            return Redirect::route('site.main')->with('error', 'Parameter tidak valid !');
        }

        $newExam = Ujian::create(array_merge($newExamData, [
            'waktu_mulai' => now(),
            'durasi_ujian' => $ujian->durasi,
            'status_ujian' => 'Sedang Dikerjakan',
        ]));

        if (!$newExam) {
            return redirect()->back()->with('error', 'Gagal memulai ujian !');
        }

        $examId = $newExam->id;

        // Simpan log aktivitas pengguna
        $logs = Auth::user()->name . ' telah membuat ujian dengan id ujian ' . $examId . ', waktu tercatat : ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('ujian.progress', ['id' => Crypt::encrypt($examId), 'param' => $request->param]);
    }

    public function progressUjian(Request $request)
    {
        // Decrypt ID
        $id = $request->id;
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ujian Tidak ditemukan!');
        }

        // Decrypt Param
        $param = $request->param;
        try {
            $param = Crypt::decrypt($param);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ujian Tidak diketahui!');
        }

        if ($param !== 'berbayar' && $param !== 'gratis') {
            return redirect()->route('site.main')->with('error', 'Parameter tidak valid !');
        }

        $checkingExam = Ujian::select('status_ujian')
            ->where('id', $id)
            ->first();
        if (!$checkingExam) {
            return redirect()->back()->with('error', 'Ujian Tidak ditemukan!');
        }

        if ($checkingExam->status_ujian === 'Selesai') {
            if ($param === 'berbayar') {
                return redirect()->route('site.tryout-berbayar');
            }

            return redirect()->route('site.tryout-gratis');
        }

        $cacheDurationExam = 100 * 60;
        $generateCacheName = Tryoutc::cacheNameGenerateExam($id);
        $cacheKey = $generateCacheName['exam'];

        $exam = Cache::remember($cacheKey, $cacheDurationExam, function () use ($id, $param) {
            $examData = DB::table('ujian')
                ->select('ujian.*')
                ->where('ujian.id', '=', $id);

            if ($param === 'berbayar') {
                $examData->addSelect('order_tryout.produk_tryout_id')
                    ->leftJoin('order_tryout', 'ujian.order_tryout_id', '=', 'order_tryout.id');
            } else {
                $examData->addSelect('limit_tryout.produk_tryout_id')
                    ->leftJoin('limit_tryout', 'ujian.limit_tryout_id', '=', 'limit_tryout.id');
            }

            return $examData->first();
        });

        if (!$exam) {
            return redirect()->back()->with('error', 'Data Ujian Tidak ditemukan!');
        }

        // Get Tryout Data
        $tryoutProduct = ProdukTryout::select('id', 'nama_tryout', 'keterangan', 'kode_soal')
            ->where('id', $exam->produk_tryout_id)
            ->first();
        if (!$tryoutProduct) {
            return redirect()->back()->with('error', 'Data Ujian Tidak ditemukan!');
        }

        $duration = $exam->durasi_ujian; // Durasi dalam menit
        $startTime = $exam->waktu_mulai;

        // Menghitung waktu selesai ujian
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('Y-m-d H:i:s');

        $titlePage = $tryoutProduct->nama_tryout;

        $customer = Customer::findOrFail(Auth::user()->customer_id);

        $data = [
            'titlePage' => $titlePage,
            'breadcumb' => 'Ujian Tryout',
            'customer' => $customer,
            'tryoutProduct' => $tryoutProduct,
            'exam' => $exam,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'param' => $request->param,
        ];

        return view('customer-panel.tryout.ujian', $data);
    }

    public function progressUjianGetQuestion(Request $request)
    {
        // Decrypt Request Param
        $examId = $request->exam_id;
        try {
            $examId = Crypt::decrypt($examId);
        } catch (\Throwable $th) {
            return response()->json(['result' => 'error', 'title' => 'Ujian Tidak ditemukan!'], 404);
        }
        $questionCode = $request->question_code;
        try {
            $questionCode = Crypt::decrypt($questionCode);
        } catch (\Throwable $th) {
            return response()->json(['result' => 'error', 'title' => 'Kode Soal Tidak diketahui!'], 200);
        }

        $cacheDurationExam = 100 * 60;
        $generateCacheName = Tryoutc::cacheNameGenerateExam($examId);
        $cacheKeyQuestion = $generateCacheName['question'];

        // Get All Question
        $questions = Cache::tags(['user_exam_question:' . $questionCode])->remember($cacheKeyQuestion, $cacheDurationExam, function () use ($questionCode) {
            return SoalUjian::where('kode_soal', $questionCode)
                ->select(
                    'soal_ujian.id',
                    'soal_ujian.kode_soal',
                    'soal_ujian.soal',
                    'soal_ujian.gambar',
                    'soal_ujian.jawaban_a',
                    'soal_ujian.jawaban_b',
                    'soal_ujian.jawaban_c',
                    'soal_ujian.jawaban_d',
                    'soal_ujian.jawaban_e',
                    'soal_ujian.klasifikasi_soal_id',
                    'klasifikasi_soal.alias as klasifikasi_alias'
                )
                ->leftJoin('klasifikasi_soal', 'soal_ujian.klasifikasi_soal_id', '=', 'klasifikasi_soal.id')
                ->orderBy('klasifikasi_soal.ordering', 'ASC')
                ->inRandomOrder()
                ->get();
        });
        $totalQuestion = $questions->count();

        // Check Total Questions
        if ($totalQuestion <= 0) {
            return response()->json(['result' => 'error', 'title' => 'Maaf tidak bisa memulai ujian, soal belum tersedia !'], 200);
        }

        // Get user saved questions
        $examProgress = DB::table('progres_ujian')
            ->where('ujian_id', $examId)
            ->pluck('jawaban', 'soal_ujian_id')
            ->toArray();
        $savedQuestions = [];
        foreach ($examProgress as $id => $answer) {
            array_push($savedQuestions, [
                'id' => $id,
                'answer' => $answer,
                'store' => 'db',
            ]);
        }

        return response()->json([
            'result' => 'success',
            'title' => 'Soal berhasil diambil.',
            'data' => [
                'questions' => $questions,
                'totalQuestion' => $totalQuestion,
                'savedQuestions' => $savedQuestions,
            ],
        ], 200);
    }

    public function progressUjianSyncAnswer(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required'],
            'question_code' => ['required'],
            'anwers' => ['required'],
        ]);

        $examId = Crypt::decrypt($data['exam_id']);
        $questionCode = Crypt::decrypt($data['question_code']);
        $anwers = $data['anwers'];

        foreach ($anwers as $answer) {
            ProgressUjian::updateOrCreate(['ujian_id' => $examId, 'soal_ujian_id' => $answer['question_id']], [
                'ujian_id' => $examId,
                'kode_soal' => $questionCode,
                'soal_ujian_id' => $answer['question_id'],
                'jawaban' => $answer['answer'],
            ]);
        }

        return response()->json([
            'result' => 'success',
            'message' => 'Jawaban berhasil disinkronisasi.',
        ], 200);
    }

    public function simpanJawaban(Request $request)
    {
        $jawabanPeserta = $request->validate([
            'ujian_id' => ['required'],
            'kode_soal' => ['required'],
            'soal_ujian_id' => ['required'],
            'jawaban' => ['required', 'string'],
        ]);

        $examId = Crypt::decrypt($jawabanPeserta['ujian_id']);
        $questionCode = Crypt::decrypt($jawabanPeserta['kode_soal']);
        $questionId = $jawabanPeserta['soal_ujian_id'];
        $answer = $jawabanPeserta['jawaban'];

        // Cek apakah jawaban sudah ada di database
        $existingJawaban = DB::table('progres_ujian')
            ->where('ujian_id', $examId)
            ->where('kode_soal', $questionCode)
            ->where('soal_ujian_id', $questionId)
            ->first();

        if ($existingJawaban) {
            if ($existingJawaban->jawaban === $answer) {
                return response()->json(['success' => true, 'message' => 'Jawaban berhasil disimpan.', 'data' => [
                    'new_saved_answered' => ['question_id' => $questionId, 'answer' => $answer],
                ]]);
            }

            // Update jawaban jika sudah ada
            ProgressUjian::where('id', $existingJawaban->id)
                ->update([
                    'jawaban' => $answer,
                ]);
        } else {
            // Simpan jawaban baru
            ProgressUjian::create([
                'ujian_id' => $examId,
                'kode_soal' => $questionCode,
                'soal_ujian_id' => $questionId,
                'jawaban' => $answer,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Jawaban berhasil disimpan.', 'data' => [
            'new_saved_answered' => ['question_id' => $questionId, 'answer' => $answer],
        ]]);
    }

    public function simpanHasilUjian(Request $request)
    {
        // Dekripsi data dari request
        $examId = Crypt::decrypt($request->id);
        $exam = Ujian::where('id', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Ujian Tidak ditemukan!');
        }

        if ($exam->status_ujian !== 'Sedang Dikerjakan') {
            return redirect()->back()->with('error', 'Status Ujian bukan Sedang Dikerjakan!');
        }

        $questionCode = null;

        $redirectRoute = 'site.tryout-berbayar';
        $examPaidType = 'berbayar';
        if ($exam->order_tryout_id && !$exam->limit_tryout_id) {
            $orderTryout = OrderTryout::where('id', $exam->order_tryout_id)
                ->select('id', 'produk_tryout_id')
                ->whereHas('tryout')
                ->with('tryout:id,kode_soal')
                ->first();

            if ($orderTryout) {
                $questionCode = $orderTryout->tryout->kode_soal;
            }
        } elseif (!$exam->order_tryout_id && $exam->limit_tryout_id) {
            $redirectRoute = 'site.tryout-gratis';
            $examPaidType = 'gratis';

            $limitTryout = LimitTryout::where('id', $exam->limit_tryout_id)
                ->select('id', 'produk_tryout_id')
                ->whereHas('tryout')
                ->with('tryout:id,kode_soal')
                ->first();

            if ($limitTryout) {
                $questionCode = $limitTryout->tryout->kode_soal;
            }
        } else {
            return redirect()->back()->with('error', 'Tipe Ujian Tidak diketahui!');
        }

        $startTime = $exam->waktu_mulai;
        $duration = $exam->durasi_ujian; // Durasi dalam menit

        // Menghitung waktu selesai ujian
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('Y-m-d H:i:s');

        $now = \Carbon\Carbon::now(); // Waktu sekarang
        $targetTime = \Carbon\Carbon::create($endTime); // Waktu yang dituju
        $diffInMinutes = $now->diffInMinutes($targetTime);
        // Dapatkan sisa waktu ujian
        if ($now->greaterThan($targetTime)) {
            $sisaWaktu = 0;
        } else {
            $sisaWaktu = floor($diffInMinutes);
        }

        $totalAnswered = ProgressUjian::where('ujian_id', $examId)->count();
        $totalQuestion = SoalUjian::where('kode_soal', $questionCode)->count();
        $totalUnAnswered = $totalQuestion - $totalAnswered;

        $exam->update([
            'waktu_berakhir' => now(),
            'sisa_waktu' => $sisaWaktu,
            'soal_terjawab' => $totalAnswered,
            'soal_belum_terjawab' => $totalUnAnswered,
            'status_ujian' => 'Selesai',
        ]);

        // Job untuk menyimpan informasi hasil ujian
        SimpanHasilUjianJob::dispatch([
            'examId' => $examId,
            'questionCode' => $questionCode,
            'param' => $examPaidType,
        ]);

        $generateCacheName = Tryoutc::cacheNameGenerateExam($examId);
        $cacheKey = $generateCacheName['exam'];
        $cacheKeyQuestion = $generateCacheName['question'];

        Cache::forget($cacheKey);
        Cache::forget($cacheKeyQuestion);

        return redirect()->route($redirectRoute)->with('Ujian berhasil disimpan !');
    }

    public function hasilUjian(Request $request)
    {
        // Decrypt ID
        $id = $request->id;
        try {
            $id = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ujian Tidak ditemukan!');
        }

        $exam = Ujian::has('hasil')->with('hasil')->find($id);
        if (!$exam) {
            return redirect()->back()->with('error', 'Ujian Tidak ditemukan!');
        }

        $productTryout = null;
        if ($exam->order_tryout_id && !$exam->limit_tryout_id) {
            $orderTryout = OrderTryout::where('id', $exam->order_tryout_id)
                ->select('id', 'produk_tryout_id')
                ->first();

            if ($orderTryout) {
                $productTryout = ProdukTryout::find($orderTryout->produk_tryout_id);
            }
        } elseif (!$exam->order_tryout_id && $exam->limit_tryout_id) {
            $limitTryout = LimitTryout::where('id', $exam->limit_tryout_id)
                ->select('id', 'produk_tryout_id')
                ->first();

            if ($limitTryout) {
                $productTryout = ProdukTryout::find($limitTryout->produk_tryout_id);
            }
        } else {
            return redirect()->back()->with('error', 'Tipe Ujian Tidak diketahui!');
        }

        if (!$productTryout) {
            return redirect()->back()->with('error', 'Ujian Tidak diketahui!');
        }

        $questionCode = $productTryout->kode_soal;

        $examResult = HasilUjian::select('id')->where('ujian_id', $exam->id)->first();
        if (!$examResult) {
            return redirect()->back()->with('error', 'Hasil Ujian tidak ditemukan atau belum diproses!');
        }
        $examResultPassinGrade = HasilPassingGrade::select('id', 'judul', 'alias', 'passing_grade', 'terjawab', 'terlewati', 'benar', 'salah', 'total_nilai')
            ->where('hasil_ujian_id', $examResult->id)
            ->get();
        if ($examResultPassinGrade->count() <= 0) {
            return redirect()->back()->with('error', 'Hasil Ujian tidak ditemukan atau belum diproses!');
        }

        $cacheKey = 'exam_answered_' . $id;
        $reviewJawaban = Cache::tags(['user_exam_question:' . $questionCode])->remember($cacheKey, 100 * 60, function () use ($id) {
            return DB::table('progres_ujian')
                ->where('progres_ujian.ujian_id', $id)
                ->select(
                    'progres_ujian.jawaban',
                    'progres_ujian.soal_ujian_id',
                    'soal_ujian.soal',
                    'soal_ujian.gambar',
                    'soal_ujian.jawaban_a',
                    'soal_ujian.jawaban_b',
                    'soal_ujian.jawaban_c',
                    'soal_ujian.jawaban_d',
                    'soal_ujian.jawaban_e',
                    'soal_ujian.poin_a',
                    'soal_ujian.poin_b',
                    'soal_ujian.poin_c',
                    'soal_ujian.poin_d',
                    'soal_ujian.poin_e',
                    'soal_ujian.berbobot',
                    'soal_ujian.kunci_jawaban',
                    'soal_ujian.review_pembahasan',
                    'klasifikasi_soal.alias as klasifikasi_alias'
                )
                ->leftJoin('soal_ujian', 'progres_ujian.soal_ujian_id', '=', 'soal_ujian.id')
                ->leftJoin('klasifikasi_soal', 'soal_ujian.klasifikasi_soal_id', '=', 'klasifikasi_soal.id')
                ->orderBy('klasifikasi_soal.ordering', 'ASC')
                ->get();
        });

        $answeredQuestions = [];
        foreach ($reviewJawaban as $question) {
            array_push($answeredQuestions, $question->soal_ujian_id);
        }

        $questionCode = $productTryout->kode_soal;
        $cacheKey = 'exam_unanswered_' . $id;
        $unAnsweredQuestions = Cache::tags(['user_exam_question:' . $questionCode])->remember($cacheKey, 100 * 60, function () use ($answeredQuestions, $questionCode) {
            return SoalUjian::where('soal_ujian.kode_soal', $questionCode)
                ->whereNotIn('soal_ujian.id', $answeredQuestions)
                ->select(
                    'soal_ujian.id',
                    'soal_ujian.soal',
                    'soal_ujian.gambar',
                    'soal_ujian.jawaban_a',
                    'soal_ujian.jawaban_b',
                    'soal_ujian.jawaban_c',
                    'soal_ujian.jawaban_d',
                    'soal_ujian.jawaban_e',
                    'soal_ujian.poin_a',
                    'soal_ujian.poin_b',
                    'soal_ujian.poin_c',
                    'soal_ujian.poin_d',
                    'soal_ujian.poin_e',
                    'soal_ujian.berbobot',
                    'soal_ujian.kunci_jawaban',
                    'soal_ujian.review_pembahasan',
                    'klasifikasi_soal.alias as klasifikasi_alias',
                )
                ->leftJoin('klasifikasi_soal', 'soal_ujian.klasifikasi_soal_id', '=', 'klasifikasi_soal.id')
                ->orderBy('klasifikasi_soal.ordering', 'ASC')
                ->get();
        });

        $data = [
            'page_title' => 'Hasil Ujian - ' . $productTryout->nama_tryout,
            'breadcumb' => $productTryout->nama_tryout,
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'productTryout' => $productTryout,
            'exam' => $exam,
            'reviewJawaban' => $reviewJawaban,
            'unAnsweredQuestions' => $unAnsweredQuestions,
            'examResultPassinGrade' => $examResultPassinGrade,
        ];

        return view('customer-panel.tryout.hasil-ujian', $data);
    }

    public function simpanTestimoni(TestimoniRequest $request)
    {
        $request->validated();

        $examResultId = $request->input('exam_result_id');
        $productId = $request->input('product_id');
        $testimoni = strip_tags($request->input('testimoni'));
        $rating = strip_tags($request->input('rating'));

        // Cek apakah sudah memberikan testimoni
        $checkTestimoni = Testimoni::where('hasil_ujian_id', $examResultId)->first();

        $saveTestimoni = null;
        if ($checkTestimoni) {
            // Check if Testimoni has set to public
            if ($checkTestimoni->publish === 'Y') {
                return redirect()->back()->with('error', 'Testimoni ini tidak dapat diubah, silahkan hubungi admin !');
            }

            $saveTestimoni = $checkTestimoni->update([
                'testimoni' => $testimoni,
                'rating' => $rating,
            ]);
        } else {
            $saveTestimoni = Testimoni::create([
                'customer_id' => Auth::user()->customer_id,
                'hasil_ujian_id' => $examResultId,
                'produk_tryout_id' => $productId,
                'testimoni' => $testimoni,
                'rating' => $rating,
            ]);
        }

        if (!$saveTestimoni) {
            return redirect()->back()->with('error', 'Testimoni gagal diberikan !');
        }

        return redirect()->back()->with('message', 'Testimoni berhasil diberikan !');
    }
}
