<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\TestimoniRequest;
use App\Jobs\SimpanHasilUjianJob;
use App\Jobs\SimpanInformasiUjianJob;
use App\Models\Customer;
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
            'exam' => 'exam_' . $id . '_data',
            'question' => 'exam_' . $id . '_questions',
        ];
    }

    public function berandaUjian(Request $request): RedirectResponse
    {
        $catatUjian = new Ujian();

        if (Crypt::decrypt($request->param) == 'berbayar') {

            // Jika ada proses ujian yang sedang berlangsung arahkan kehalaman ujian
            $cekUjian = Ujian::where('order_tryout_id', Crypt::decrypt($request->id))->first();
            if ($cekUjian) {
                if ($cekUjian->status_ujian == 'Sedang Dikerjakan') {
                    return redirect()->route('ujian.progress', ['id' => Crypt::encrypt($cekUjian->id), 'param' => $request->param]);
                }
            }
            $ujian = DB::table('order_tryout')->select(
                'order_tryout.id',
                'produk_tryout.id as produk_id',
                'pengaturan_tryout.durasi'
            )->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->where('order_tryout.id', '=', Crypt::decrypt($request->id))->first();

            $catatUjian->order_tryout_id = Crypt::decrypt($request->id);
        } elseif (Crypt::decrypt($request->param) == 'gratis') {
            // Jika ada proses ujian yang sedang berlangsung arahkan kehalaman ujian
            $cekUjian = Ujian::where('limit_tryout_id', Crypt::decrypt($request->id))->first();
            if ($cekUjian) {
                if ($cekUjian->status_ujian == 'Sedang Dikerjakan') {
                    return redirect()->route('ujian.progress', ['id' => Crypt::encrypt($cekUjian->id), 'param' => $request->param]);
                }
            }

            $ujian = DB::table('limit_tryout')->select(
                'limit_tryout.id',
                'produk_tryout.id as produk_id',
                'pengaturan_tryout.durasi'
            )->leftJoin('produk_tryout', 'limit_tryout.produk_tryout_id', '=', 'produk_tryout.id')
                ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
                ->where('limit_tryout.id', '=', Crypt::decrypt($request->id))->first();

            $catatUjian->limit_tryout_id = Crypt::decrypt($request->id);
        } else {
            return Redirect::route('site.main')->with('error', 'Parameter tidak valid !');
        }

        $ujianID = rand(1, 999) . rand(1, 99);

        $catatUjian->id = $ujianID;
        $catatUjian->waktu_mulai = now();
        $catatUjian->durasi_ujian = $ujian->durasi;
        $catatUjian->status_ujian = 'Sedang Dikerjakan';

        if ($catatUjian->save()) {
            // Simpan log aktivitas pengguna
            $logs = Auth::user()->name . ' telah membuat ujian dengan id ujian ' . $ujianID . ', waktu tercatat : ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return redirect()->route('ujian.progress', ['id' => Crypt::encrypt($ujianID), 'param' => $request->param]);
        }
        return redirect()->back()->with('error', 'Gagal memulai ujian !');
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
        $cacheKeyQuestion = $generateCacheName['question'];

        Cache::forget($cacheKey);
        Cache::forget($cacheKeyQuestion);

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

        // Check if exam already ended
        $currentTime = now();
        if ($endTime < $currentTime) {
            return redirect()->route('ujian.simpan-hasil', ['id' => Crypt::encrypt($exam->id)]);
        }

        $titlePage = $tryoutProduct->nama_tryout;

        $customer = Customer::findOrFail(Auth::user()->customer_id);

        // Get All Question
        $questions = Cache::remember($cacheKeyQuestion, $cacheDurationExam, function () use ($tryoutProduct) {
            return SoalUjian::select('soal_ujian.id', 'soal_ujian.kode_soal', 'soal_ujian.soal', 'soal_ujian.gambar', 'soal_ujian.jawaban_a', 'soal_ujian.jawaban_b', 'soal_ujian.jawaban_c', 'soal_ujian.jawaban_d', 'soal_ujian.jawaban_e')
                ->where('kode_soal', '=', $tryoutProduct->kode_soal)
                ->get();
        });
        $totalQuestion = $questions->count();

        // Check Total Questions
        if ($totalQuestion <= 0) {
            return redirect()->back()->with('error', 'Maaf tidak bisa memulai ujian, soal belum tersedia !');
        }

        // Get user saved questions
        $savedQuestions = DB::table('progres_ujian')
            ->where('ujian_id', $id)
            ->pluck('jawaban', 'soal_ujian_id')
            ->toArray();

        $data = [
            'titlePage' => $titlePage,
            'breadcumb' => 'Ujian Tryout',
            'customer' => $customer,
            'tryoutProduct' => $tryoutProduct,
            'exam' => $exam,
            'totalQuestion' => $totalQuestion,
            'savedQuestions' => $savedQuestions,
            'questions' => $questions,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'param' => $request->param,
        ];

        return view('customer-panel.tryout.ujian', $data);
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
            DB::table('progres_ujian')
                ->where('id', $existingJawaban->id)
                ->update([
                    'jawaban' => $answer,
                ]);
        } else {
            $progressID = rand(1, 999) . rand(1, 99) . rand(1, 9);

            // Simpan jawaban baru
            DB::table('progres_ujian')->insert([
                'id' => $progressID,
                'ujian_id' => $examId,
                'kode_soal' => $questionCode,
                'soal_ujian_id' => $questionId,
                'jawaban' => $answer,
            ]);

        }

        $totalAnswered = ProgressUjian::where('ujian_id', $examId)->count();
        $totalQuestion = Cache::remember('totalQuestion_' . $questionCode, 100 * 60, function () use ($questionCode) {
            return SoalUjian::where('kode_soal', $questionCode)->count();
        });
        $totalUnAnswered = $totalQuestion - $totalAnswered;

        $exam = Ujian::find($examId);
        if (!$exam) {
            return response()->json(['success' => false, 'message' => 'Data Ujian tidak ditemukan.']);
        }

        $exam->soal_terjawab = $totalAnswered;
        $exam->soal_belum_terjawab = $totalUnAnswered;

        $save = $exam->save();
        if (!$save) {
            return response()->json(['success' => false, 'message' => 'Jawaban gagal disimpan, silahkan coba kembali.']);
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

        $dataInfoUjian = [
            'ujianID' => $examId,
            'kodeSoal' => $questionCode,
            'param' => $examPaidType,
        ];

        $ujianTersimpan = HasilUjian::where('ujian_id', $examId)->first();
        if (!$ujianTersimpan) {
            // Job untuk menyimpan informasi ujian dan hasil ujian
            SimpanHasilUjianJob::dispatch($dataInfoUjian);
            SimpanInformasiUjianJob::dispatch($dataInfoUjian);
        }

        return redirect()->route($redirectRoute)->with('Ujian berhasil disimpan !');
    }

    public function hasilUjian(Request $request)
    {
        $informasiUjian = HasilUjian::find(Crypt::decrypt($request->id))->first();
        $informasitryout = ProdukTryout::find(Crypt::decrypt($request->produkID))->first();

        $hasilUjianID = Crypt::decrypt($request->ujianID);
        $cacheKey = 'Exam' . $hasilUjianID;
        $reviewJawaban = Cache::remember($cacheKey, 10 * 60, function () use ($hasilUjianID) {
            return
            DB::table('progres_ujian')->select(
                'progres_ujian.*',
                'soal_ujian.soal',
                'soal_ujian.gambar',
                'soal_ujian.jawaban_a',
                'soal_ujian.jawaban_b',
                'soal_ujian.jawaban_c',
                'soal_ujian.jawaban_d',
                'soal_ujian.jawaban_e',
                'soal_ujian.kunci_jawaban',
                'soal_ujian.review_pembahasan'
            )->leftJoin('soal_ujian', 'progres_ujian.soal_ujian_id', '=', 'soal_ujian.id')
                ->where('ujian_id', $hasilUjianID)->get();
        });

        $data = [
            'page_title' => 'Hasil Ujian',
            'breadcumb' => $informasitryout->nama_tryout,
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'informasiUjian' => $informasiUjian,
            'reviewJawaban' => $reviewJawaban,
            'informasiTryout' => $informasitryout,
        ];

        return view('customer-panel.tryout.hasil-ujian', $data);
    }

    public function simpanTestimoni(TestimoniRequest $request)
    {
        $request->validated();

        // Cek apakah sudah memberikan testimoni
        $cekTestimoni = Testimoni::where('hasil_ujian_id', $request->input('ujianID'))->first();
        if ($cekTestimoni) {
            Testimoni::where('hasil_ujian_id', $request->input('ujianID'))->delete();
        }

        $testimoni = new Testimoni();
        $testimoni->id = rand(1, 999) . rand(1, 99);
        $testimoni->customer_id = Auth::user()->customer_id;
        $testimoni->produk_tryout_id = Crypt::decrypt($request->input('produkID'));
        $testimoni->hasil_ujian_id = $request->input('ujianID');
        $testimoni->testimoni = $request->input('testimoni');
        $testimoni->rating = $request->input('rating');

        if ($testimoni->save()) {
            return redirect()->back()->with('message', 'Testimoni berhasil diberikan !');
        } else {
            return redirect()->back()->with('error', 'Testimoni gagal diberikan !');
        }
    }
}
