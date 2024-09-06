<?php

namespace App\Http\Controllers\Customer;

use App\Models\Ujian;
use App\Models\Customer;
use App\Models\HasilUjian;
use App\Helpers\RecordLogs;
use Illuminate\Http\Request;
use App\Jobs\SimpanJawabanJob;
use App\Jobs\SimpanHasilUjianJob;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\TestimoniRequest;
use App\Jobs\SimpanInformasiUjianJob;
use App\Models\ProdukTryout;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class Tryoutc extends Controller
{
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
        $progresUjian = Ujian::find(Crypt::decrypt($request->id));

        if ($progresUjian->status_ujian == 'Selesai') {
            if (Crypt::decrypt($request->param) == 'berbayar') {
                return Redirect::route('site.tryout-berbayar');
            } else {
                return Redirect::route('site.tryout-gratis');
            }
        }
        // Dekripsi ID ujian dari request
        $ujianId = Crypt::decrypt($request->id);
        if (Crypt::decrypt($request->param) == 'berbayar') {

            $ujianTryout = DB::table('ujian')->select('ujian.*', 'order_tryout.produk_tryout_id')->leftJoin('order_tryout', 'ujian.order_tryout_id', '=', 'order_tryout.id')->where('ujian.id', '=', Crypt::decrypt($request->id))->first();
            // Cek apakah data ujian sudah ada di cache
            $cacheKey = 'ujianBerbayar_' . $ujianId;
            $ujian = Cache::remember($cacheKey, $ujianTryout->durasi_ujian * 60, function () use ($ujianId) {
                return DB::table('ujian')
                    ->select('ujian.*', 'order_tryout.produk_tryout_id')
                    ->leftJoin('order_tryout', 'ujian.order_tryout_id', '=', 'order_tryout.id')
                    ->where('ujian.id', '=', $ujianId)
                    ->first();
            });
            $cacheKeySoal = 'soal_ujianBerbayar_' . $ujian->produk_tryout_id;
        } else {
            $ujianTryout = DB::table('ujian')->select('ujian.*', 'limit_tryout.produk_tryout_id')->leftJoin('limit_tryout', 'ujian.limit_tryout_id', '=', 'limit_tryout.id')->where('ujian.id', '=', Crypt::decrypt($request->id))->first();
            // Cek apakah data ujian sudah ada di cache
            $cacheKey = 'ujianGratis_' . $ujianId;
            $ujian = Cache::remember($cacheKey, $ujianTryout->durasi_ujian * 60, function () use ($ujianId) {
                return DB::table('ujian')
                    ->select('ujian.*', 'limit_tryout.produk_tryout_id')
                    ->leftJoin('limit_tryout', 'ujian.limit_tryout_id', '=', 'limit_tryout.id')
                    ->where('ujian.id', '=', $ujianId)
                    ->first();
            });
            $cacheKeySoal = 'soal_ujianGratis_' . $ujian->produk_tryout_id;
        }

        // Checking soal terlebih dahulu 
        $checkingSoal = DB::table('soal_ujian')
            ->select('soal_ujian.*', 'produk_tryout.kode_soal')
            ->leftJoin('produk_tryout', 'soal_ujian.kode_soal', '=', 'produk_tryout.kode_soal')
            ->where('produk_tryout.id', '=', $ujian->produk_tryout_id);

        if ($checkingSoal->first()) {
            // Ambil seluruh soal ujian dari cache atau database jika belum ada di cache
            $soalUjian = Cache::remember(
                $cacheKeySoal,
                6000,
                function () use ($ujian) {
                    return DB::table('soal_ujian')
                        ->select('soal_ujian.*', 'produk_tryout.kode_soal', 'klasifikasi_soal.alias', 'klasifikasi_soal.created_at')
                        ->leftJoin('produk_tryout', 'soal_ujian.kode_soal', '=', 'produk_tryout.kode_soal')
                        ->leftJoin('klasifikasi_soal', 'soal_ujian.klasifikasi_soal_id', '=', 'klasifikasi_soal.id')
                        ->where('produk_tryout.id', '=', $ujian->produk_tryout_id)->orderBy('klasifikasi_soal.created_at', 'ASC')->get();
                }
            );
        } else {
            return redirect()->back()->with('error', 'Maaf tidak bisa memulai ujian, soal belum tersedia !');
        }

        // Ambil halaman saat ini
        $currentPage = $request->input('page', 1);
        $perPage = 1; // Soal per halaman
        $offset = ($currentPage - 1) * $perPage;

        // Ambil soal untuk halaman saat ini
        $currentSoal = $soalUjian->slice($offset, $perPage)->all();

        // Buat paginasi manual
        $soalUjianPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentSoal,
            $soalUjian->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $startTime = $ujianTryout->waktu_mulai;
        $duration = $ujianTryout->durasi_ujian; // Durasi dalam menit

        // Menghitung waktu selesai ujian
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('Y-m-d H:i:s');

        // Ambil jawaban yang sudah diisi
        $jawabanTersimpan = DB::table('progres_ujian')
            ->where('ujian_id', $ujianId)
            ->pluck('jawaban', 'soal_ujian_id')
            ->toArray();

        if (Crypt::decrypt($request->param) == 'berbayar') {
            $page_title = 'Ujian Tryout Berbayar';
        } elseif (Crypt::decrypt($request->param) == 'gratis') {
            $page_title = 'Ujian Tryout Gratis';
        } else {
            return Redirect::route('site.main')->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $page_title,
            'breadcumb' => 'Ujian Tryout',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'ujianId' => $ujianId,
            'ujian' => $ujian,
            'totalSoal' => $soalUjian->count(),
            'soalUjianAll' => $soalUjian,
            'jawabSoal' => $ujianTryout,
            'soalUjian' => $soalUjianPaginated,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'jawabanTersimpan' => $jawabanTersimpan,
            'param' => $request->param
        ];

        return view('customer-panel.tryout.ujian', $data);
    }

    public function simpanJawaban(Request $request)
    {
        $jawabanPeserta = $request->validate([
            'ujian_id' => ['required'],
            'soal_ujian_id' => ['required'],
            'kode_soal' => ['required'],
            'jawaban' => ['required', 'string'],
        ]);

        // Job untuk menyimpan jawaban peserta
        SimpanJawabanJob::dispatch($jawabanPeserta);

        return response()->json(['success' => true, 'message' => 'Jawaban berhasil disimpan.']);
    }

    public function simpanHasilUjian(Request $request)
    {
        // Dekripsi data dari request
        $ujianId = Crypt::decrypt($request->id);
        $kodeSoal = Crypt::decrypt($request->kode_soal);
        $param = Crypt::decrypt($request->param);

        $dataInfoUjian = [
            'ujianID' => $ujianId,
            'kodeSoal' => $kodeSoal,
            'param' => $param
        ];

        $ujianTersimpan = HasilUjian::where('ujian_id', $ujianId)->first();
        if (!$ujianTersimpan) {

            // Job untuk menyimpan informasi ujian dan hasil ujian
            SimpanHasilUjianJob::dispatch($dataInfoUjian);
            SimpanInformasiUjianJob::dispatch($dataInfoUjian);
        }

        if (Crypt::decrypt($request->param) == 'berbayar') {
            return Redirect::route('site.tryout-berbayar')->with('Ujian berhasil disimpan !');
        } elseif (Crypt::decrypt($request->param) == 'gratis') {
            return Redirect::route('site.tryout-gratis')->with('Ujian berhasil disimpan !');
        } else {
            return Redirect::route('site.main')->with('error', 'Parameter tidak valid !');
        }
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
            'informasiTryout' => $informasitryout
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
