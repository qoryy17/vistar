<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\ProdukTryoutRequest;
use App\Http\Requests\Panel\SoalRequest;
use App\Jobs\SendAcceptTryoutGratisJob;
use App\Jobs\SendDeniedTryoutGratisJob;
use App\Models\KategoriProduk;
use App\Models\KlasifikasiSoal;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use App\Models\PengaturanTryout;
use App\Models\ProdukTryout;
use App\Models\SoalUjian;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Tryouts extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Produk Tryout',
            'bc1' => 'Manajemen Tryout',
            'bc2' => 'Produk Tryout',
            'tryouts' => DB::table('produk_tryout')->select('produk_tryout.*', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'pengaturan_tryout.passing_grade', 'kategori_produk.judul', 'kategori_produk.status as produk_status')->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')->orderBy('created_at', 'DESC')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.tryout.data-produk-tryout', $data);
    }

    public function formProdukTryout($param = null, $id = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Produk Tryout';
            $formParam = Crypt::encrypt('add');
            $tryout = '';
            $pengaturan = '';
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Produk Tryout';
            $formParam = Crypt::encrypt('update');
            $tryout = ProdukTryout::findOrFail(Crypt::decrypt($id));
            $pengaturan = PengaturanTryout::findOrFail($tryout->pengaturan_tryout_id);
        } else {
            return Redirect::route('tryouts.index')->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $form_title,
            'bc1' => 'Manajemen Tryout',
            'bc2' => 'Produk Tryout',
            'kategori' => KategoriProduk::where('aktif', 'Y'),
            'tryout' => $tryout,
            'pengaturan' => $pengaturan,
            'formParam' => $formParam,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.tryout.form-data-produk-tryout', $data);
    }

    public function simpanProdukTryout(ProdukTryoutRequest $request): RedirectResponse
    {
        // Autentifikasi user
        $users = Auth::user();
        // Validasi inputan
        $request->validated();

        $savedDataPengaturanTryout = [
            'harga' => intval(htmlspecialchars($request->input('harga'))),
            'harga_promo' => intval(htmlspecialchars($request->input('hargaPromo'))),
            'durasi' => intval(htmlspecialchars($request->input('durasiUjian'))),
        ];

        if (!$request->input('nilaiKeluar')) {
            $savedDataPengaturanTryout['nilai_keluar'] = 'T';
        } else {
            $savedDataPengaturanTryout['nilai_keluar'] = htmlspecialchars($request->input('nilaiKeluar'));
        }
        if (!$request->input('grafikEvaluasi')) {
            $savedDataPengaturanTryout['grafik_evaluasi'] = 'T';
        } else {
            $savedDataPengaturanTryout['grafik_evaluasi'] = htmlspecialchars($request->input('grafikEvaluasi'));
        }
        if (!$request->input('reviewPembahasan')) {
            $savedDataPengaturanTryout['review_pembahasan'] = 'T';
        } else {
            $savedDataPengaturanTryout['review_pembahasan'] = htmlspecialchars($request->input('reviewPembahasan'));
        }
        if (!$request->input('ulangUjian')) {
            $savedDataPengaturanTryout['ulang_ujian'] = 'T';
        } else {
            $savedDataPengaturanTryout['ulang_ujian'] = htmlspecialchars($request->input('ulangUjian'));
        }
        $savedDataPengaturanTryout['masa_aktif'] = intval(htmlspecialchars($request->input('masaAktif')));
        $savedDataPengaturanTryout['passing_grade'] = htmlspecialchars($request->input('passingGrade'));

        $savedDataProdukTryout = [
            'nama_tryout' => htmlspecialchars($request->input('namaTryout')),
            'keterangan' => htmlspecialchars($request->input('keterangan')),
            'kategori_produk_id' => htmlspecialchars($request->input('kategori')),
            'status' => htmlspecialchars($request->input('status')),
        ];

        if (Crypt::decrypt($request->input('formParameter')) == 'add') {
            $pengaturanTryout = PengaturanTryout::create($savedDataPengaturanTryout);
            if (!$pengaturanTryout) {
                return redirect()->back()->with('error', 'Pengaturan Tryout gagal dibuat!')->withInput();
            }

            // Upload thumbnail produk tryout
            if (!$request->file('thumbnail')) {
                return redirect()->back()->with('error', 'Thumbnail belum di unggah ulang !')->withInput();
            }
            $fileThumbnail = $request->file('thumbnail');
            $fileHashname = $fileThumbnail->hashName();

            $fileUpload = $fileThumbnail->storeAs('public/tryout', $fileHashname);
            if (!$fileUpload) {
                return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
            }

            $savedDataProdukTryout['thumbnail'] = $fileHashname;
            $savedDataProdukTryout['user_id'] = $users->id;
            $savedDataProdukTryout['pengaturan_tryout_id'] = $pengaturanTryout->id;
            $savedDataProdukTryout['kode_soal'] = Str::random(5) . rand(1, 999);

            $saveProdukTryout = ProdukTryout::create($savedDataProdukTryout);

            // Catatan log
            $logs = $users->name . ' telah menambahkan produk tryout ' . htmlspecialchars($request->input('nama')) . ' waktu tercatat :  ' . now();
            $message = 'Produk tryout berhasil disimpan !';
            $error = 'Produk tryout gagal disimpan !';
        } elseif (Crypt::decrypt($request->input('formParameter')) == 'update') {
            // Cari produk tryout berdasarkan produk id
            $produkTryout = ProdukTryout::findOrFail(htmlspecialchars($request->input('produkID')));
            $pengaturanTryout = PengaturanTryout::findOrFail($produkTryout->pengaturan_tryout_id);

            $savePengaturanTryout = $pengaturanTryout->update($savedDataPengaturanTryout);
            if (!$savePengaturanTryout) {
                return redirect()->back()->with('error', 'Pengaturan Tryout gagal diperbarui!')->withInput();
            }

            if ($request->hasFile('thumbnail')) {
                // Upload thumbnail produk tryout
                $fileThumbnail = $request->file('thumbnail');
                $fileHashname = $fileThumbnail->hashName();
                $fileUpload = $fileThumbnail->storeAs('public/tryout', $fileHashname);

                if (!$fileUpload) {
                    return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
                }

                // Hapus thumbnail yang lama
                Storage::disk('public')->delete('tryout/' . $produkTryout->thumbnail);
                $savedDataProdukTryout['thumbnail'] = $fileHashname;
            }

            $saveProdukTryout = $produkTryout->update($savedDataProdukTryout);

            // Catatan log
            $logs = $users->name . ' telah memperbarui produk tryout dengan ID' . htmlspecialchars($request->input('produkID')) . ' waktu tercatat :  ' . now();
            $message = 'Produk tryout berhasil diperbarui !';
            $error = 'Produk tryout gagal diperbarui !';
        } else {
            return Redirect::route('tryouts.index')->with('error', 'Parameter tidak valid !');
        }

        if (!$saveProdukTryout) {
            return Redirect::route('tryouts.index')->with('error', $error)->withInput();
        }

        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return Redirect::route('tryouts.index')->with('message', $message);
    }

    public function hapusProdukTryout(Request $request): RedirectResponse
    {
        $produkTryout = ProdukTryout::findOrFail(Crypt::decrypt($request->id));
        if ($produkTryout) {
            $users = Auth::user();
            $pengaturanTryout = PengaturanTryout::findOrFail($produkTryout->pengaturan_tryout_id);
            $orderTryout = OrderTryout::where('produk_tryout_id', $produkTryout->id)->first();
            $limitTryout = LimitTryout::where('produk_tryout_id', $produkTryout->id)->first();

            if (!$orderTryout && !$limitTryout) {
                if (!$pengaturanTryout) {
                    return Redirect::route('tryouts.index')->with('error', 'ID pengaturan tidak ditemukan !');
                }

                $soalUjian = SoalUjian::select('id', 'gambar')
                    ->where('kode_soal', $produkTryout->kode_soal)
                    ->get();
                foreach ($soalUjian as $soal) {
                    $gambar = $soal->gambar;
                    if ($gambar && Storage::disk('public')->exists('soal/' . $gambar)) {
                        Storage::disk('public')->delete('soal/' . $gambar);
                    }
                    $soal->delete();
                }

                $thumbnail = $produkTryout->thumbnail;
                if ($thumbnail && Storage::disk('public')->exists('tryout/' . $thumbnail)) {
                    Storage::disk('public')->delete('tryout/' . $thumbnail);
                }

                $produkTryout->delete();
                $pengaturanTryout->delete();

                // Simpan logs aktivitas pengguna
                $logs = $users->name . ' telah menghapus produk tryout dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::route('tryouts.index')->with('message', 'Produk tryout berhasil dihapus !');
            } else {
                return Redirect::route('tryouts.index')->with('error', 'Produk sudah diorder tidak dapat dihapus !');
            }
        }
        return Redirect::route('tryouts.index')->with('error', 'Produk tryout gagal dihapus !');
    }

    public function detailProduk($id = null)
    {
        $tryout = ProdukTryout::findOrFail(Crypt::decrypt($id));
        $pengaturan = PengaturanTryout::findOrFail($tryout->pengaturan_tryout_id);
        $kategori = KategoriProduk::findOrFail($tryout->kategori_produk_id);
        $klasifikasiSoal = KlasifikasiSoal::all();
        $totalSoal = SoalUjian::where('kode_soal', $tryout->kode_soal)->count();

        $data = [
            'page_title' => 'Detail Produk Tryout',
            'bc1' => 'Manajemen Tryout',
            'bc2' => 'Detail Produk Tryout',
            'tryout' => $tryout,
            'kategori' => $kategori,
            'pengaturan' => $pengaturan,
            'klasifikasi' => $klasifikasiSoal,
            'totalSoal' => $totalSoal,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.tryout.detail-produk-tryout', $data);
    }

    public function soalTryout($id = null)
    {
        $data = [
            'page_title' => 'Kelola Soal',
            'bc1' => 'Manajemen Produk',
            'bc2' => 'Produk Tryout',
            'kode_soal' => $id,
            'soal' => DB::table('soal_ujian')
                ->select('soal_ujian.*', 'klasifikasi_soal.judul', 'klasifikasi_soal.alias', 'klasifikasi_soal.passing_grade')
                ->leftJoin('klasifikasi_soal', 'klasifikasi_soal.id', '=', 'soal_ujian.klasifikasi_soal_id')
                ->where('soal_ujian.kode_soal', '=', Crypt::decrypt($id))
                ->orderBy('soal_ujian.updated_at', 'DESC')
                ->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.tryout.data-soal-tryout', $data);
    }

    public function formSoalTryout($param = null, $id = null, $soal = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Soal Tryout';
            $formParam = Crypt::encrypt('add');
            $soal = null;
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Soal Tryout';
            $formParam = Crypt::encrypt('update');
            $soal = SoalUjian::with('klasifikasiSoal:id,judul,alias')
                ->where('soal_ujian.id', '=', Crypt::decrypt($soal))
                ->first();
        } else {
            return Redirect::route('tryouts.soal')->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $form_title,
            'bc1' => 'Kelola Soal',
            'bc2' => $form_title,
            'kode_soal' => $id,
            'soal' => $soal,
            'formParam' => $formParam,
            'klasifikasi_soal' => KlasifikasiSoal::whereNotIn('aktif', ['T'])->orderBy('created_at', 'ASC')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.tryout.form-data-soal-tryout', $data);
    }

    public function simpanSoalUjian(SoalRequest $request): RedirectResponse
    {
        // Validasi inputan
        $request->validated();

        // Autentifikasi user
        $users = Auth::user();

        $kode_soal = htmlspecialchars(Crypt::decrypt($request->input('kodeSoal')));
        $soal = $request->input('soal');
        $jawaban_a = $request->input('jawaban_a');
        $jawaban_b = $request->input('jawaban_b');
        $jawaban_c = $request->input('jawaban_c');
        $jawaban_d = $request->input('jawaban_d');
        $jawaban_e = $request->input('jawaban_e');
        $points = [
            'a' => htmlspecialchars($request->input('poin_a')),
            'b' => htmlspecialchars($request->input('poin_b')),
            'c' => htmlspecialchars($request->input('poin_c')),
            'd' => htmlspecialchars($request->input('poin_d')),
            'e' => htmlspecialchars($request->input('poin_e')),
        ];
        $berbobot = htmlspecialchars($request->input('berbobot'));
        $kunci_jawaban = $request->input('kunciJawaban');
        $klasifikasi_soal_id = htmlspecialchars($request->input('klasifikasi'));
        $review_pembahasan = $request->input('reviewPembahasan');

        $formParameter = Crypt::decrypt($request->input('formParameter'));

        if ($formParameter !== 'add' && $formParameter !== 'update') {
            return redirect()->back()->with('error', 'Parameter tidak valid !')->withInput();
        }

        // Get max of options
        $max_options = array_keys($points, max($points));

        // Check if question not berbobot, validate kunci_jawaban
        if ($berbobot === "0") {
            if ($kunci_jawaban === '') {
                return redirect()->back()->with('error', 'Silahkan pilih Kunci Jawaban karna pilihan jawaban tidak berbobot !')->withInput();
            }

            $max = intval($points[$max_options[0]]);

            if ($kunci_jawaban === 'A') {
                $points = ['a' => $max, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => 0];
            }
            if ($kunci_jawaban === 'B') {
                $points = ['a' => 0, 'b' => $max, 'c' => 0, 'd' => 0, 'e' => 0];
            }
            if ($kunci_jawaban === 'C') {
                $points = ['a' => 0, 'b' => 0, 'c' => $max, 'd' => 0, 'e' => 0];
            }
            if ($kunci_jawaban === 'D') {
                $points = ['a' => 0, 'b' => 0, 'c' => 0, 'd' => $max, 'e' => 0];
            }
            if ($kunci_jawaban === 'E') {
                $points = ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => $max];
            }

            if ($max === 0) {
                return redirect()->back()->with('error', 'Silahkan masukkan bobot pada Pilihan Jawaban ' . $kunci_jawaban . ' !')->withInput();
            }
        } else {
            if (count($max_options) > 1) {
                return redirect()->back()->with('error', 'Untuk Soal berbobot, silahkan masukkan bobot pada tiap Pilihan Jawaban dengan bobot yang berbeda !')->withInput();
            }

            $kunci_jawaban = strtoupper($max_options[0]);
        }

        $savedData = [
            'kode_soal' => $kode_soal,
            'soal' => $soal,

            'jawaban_a' => $jawaban_a,
            'jawaban_b' => $jawaban_b,
            'jawaban_c' => $jawaban_c,
            'jawaban_d' => $jawaban_d,
            'jawaban_e' => $jawaban_e,
            'poin_a' => $points['a'],
            'poin_b' => $points['b'],
            'poin_c' => $points['c'],
            'poin_d' => $points['d'],
            'poin_e' => $points['e'],
            'berbobot' => $berbobot,
            'kunci_jawaban' => $kunci_jawaban,
            'klasifikasi_soal_id' => $klasifikasi_soal_id,
            'review_pembahasan' => $review_pembahasan,
        ];

        $save = null;
        $logs = null;

        $message = 'Soal ujian berhasil disimpan !';
        $error = 'Soal ujian gagal disimpan !';

        if ($formParameter === 'add') {
            if ($request->hasFile('gambar')) {
                $fileSoalGambar = $request->file('gambar');
                $fileHashname = $fileSoalGambar->hashName();

                $fileUpload = $fileSoalGambar->storeAs('public/soal', $fileHashname);
                if (!$fileUpload) {
                    return redirect()->back()->with('error', 'Unggah gambar gagal !')->withInput();
                }
                $savedData['gambar'] = $fileHashname;
            }

            $save = SoalUjian::create($savedData);

            // Catatan log
            $logs = $users->name . ' telah menambahkan soal dengan ID:' . $save['id'] . ' waktu tercatat :  ' . now();
        } elseif ($formParameter == 'update') {
            $idSoal = Crypt::decrypt($request->input('idSoal'));

            $soalUjian = SoalUjian::find($idSoal);
            if (!$soalUjian) {
                return redirect()->back()->with('error', 'Soal Ujian tidak ditemukan!')->withInput();
            }

            if ($request->hasFile('gambar')) {
                // Upload gambar produk tryout
                $fileSoalGambar = $request->file('gambar');
                $fileHashname = $fileSoalGambar->hashName();
                $fileUpload = $fileSoalGambar->storeAs('public/soal', $fileHashname);

                if (!$fileUpload) {
                    return redirect()->back()->with('error', 'Unggah gambar gagal !')->withInput();
                }

                // Hapus gambar yang lama
                Storage::disk('public')->delete('soal/' . $soalUjian->gambar);

                $savedData['gambar'] = $fileHashname;
            }

            $save = $soalUjian->update($savedData);

            // Catatan log
            $logs = $users->name . ' telah memperbarui soal dengan ID ' . $soalUjian->id . ' waktu tercatat :  ' . now();
            $message = 'Soal ujian berhasil diperbarui !';
            $error = 'Soal ujian gagal diperbarui !';
        }

        if (!$save) {
            return redirect()->back()->with('error', $error)->withInput();
        }

        if ($logs) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        }

        return redirect()->route('tryouts.soal', ['id' => $request->input('kodeSoal')])->with('message', $message);
    }

    public function hapusSoalUjian(Request $request): RedirectResponse
    {
        $soalUjian = SoalUjian::findOrFail(Crypt::decrypt($request->id));
        if (!$soalUjian) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan !');
        }
        $gambar = $soalUjian->gambar;

        $users = Auth::user();
        $delete = $soalUjian->delete();
        if (!$delete) {
            return redirect()->back()->with('error', 'Soal gagal dihapus !');
        }

        // Hapus gambar
        if ($gambar && Storage::disk('public')->exists('soal/' . $gambar)) {
            Storage::disk('public')->delete('soal/' . $gambar);
        }

        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus soal ujian dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('tryouts.soal', ['id' => Crypt::encrypt($soalUjian->kode_soal)])->with('message', 'Soal berhasil dihapus !');
    }

    public function duplikatProdukTryout(Request $request): RedirectResponse
    {
        $users = Auth::user();

        $produkTryout = ProdukTryout::findOrFail(Crypt::decrypt($request->id));
        $pengaturanTryout = PengaturanTryout::findOrFail($produkTryout->pengaturan_tryout_id);

        try {
            $deletedImages = [];

            DB::beginTransaction();

            $createPengaturanTryout = PengaturanTryout::create([
                'harga' => $pengaturanTryout->harga,
                'harga_promo' => $pengaturanTryout->harga_promo,
                'durasi' => $pengaturanTryout->durasi,
                'nilai_keluar' => $pengaturanTryout->nilai_keluar,
                'grafik_evaluasi' => $pengaturanTryout->grafik_evaluasi,
                'review_pembahasan' => $pengaturanTryout->review_pembahasan,
                'ulang_ujian' => $pengaturanTryout->ulang_ujian,
                'masa_aktif' => $pengaturanTryout->masa_aktif,
                'passing_grade' => $pengaturanTryout->passing_grade,
            ]);
            if (!$createPengaturanTryout) {
                throw new Exception('Gagal menduplikat Pengaturan Tryout');
            }

            $thumbnail = $produkTryout->thumbnail;
            $fileThumbnail = null;
            if ($thumbnail && Storage::disk('public')->exists('tryout/' . $thumbnail)) {
                $fileThumbnail = 'copy--' . time() . '-' . $thumbnail;
                $path = 'tryout/' . $fileThumbnail;
                Storage::disk('public')->copy('tryout/' . $thumbnail, $path);

                array_push($deletedImages, $path);
            }

            $kodeSoalGenerate = Str::random(5) . rand(1, 999);

            $createProdukTryout = ProdukTryout::create([
                'nama_tryout' => "Duplikat " . $produkTryout->nama_tryout,
                'keterangan' => $produkTryout->keterangan,
                'kode_soal' => $kodeSoalGenerate,
                'pengaturan_tryout_id' => $createPengaturanTryout['id'],
                'user_id' => $users->id,
                'kategori_produk_id' => $produkTryout->kategori_produk_id,
                'status' => 'Tidak Tersedia',
                'thumbnail' => $fileThumbnail,
            ]);
            if (!$createProdukTryout) {
                throw new Exception('Gagal menduplikat Produk Tryout');
            }

            $savedDataQuestions = [];

            $soal = DB::table('soal_ujian')->where('kode_soal', '=', $produkTryout->kode_soal)->get();
            foreach ($soal as $soalDuplikat) {
                $savedDataQuestion = [
                    'kode_soal' => $kodeSoalGenerate,
                    'soal' => $soalDuplikat->soal,
                    'jawaban_a' => $soalDuplikat->jawaban_a,
                    'jawaban_b' => $soalDuplikat->jawaban_b,
                    'jawaban_c' => $soalDuplikat->jawaban_c,
                    'jawaban_d' => $soalDuplikat->jawaban_d,
                    'jawaban_e' => $soalDuplikat->jawaban_e,
                    'poin_a' => $soalDuplikat->poin_a,
                    'poin_b' => $soalDuplikat->poin_b,
                    'poin_c' => $soalDuplikat->poin_c,
                    'poin_d' => $soalDuplikat->poin_d,
                    'poin_e' => $soalDuplikat->poin_e,
                    'berbobot' => $soalDuplikat->berbobot,
                    'kunci_jawaban' => $soalDuplikat->kunci_jawaban,
                    'klasifikasi_soal_id' => $soalDuplikat->klasifikasi_soal_id,
                    'review_pembahasan' => $soalDuplikat->review_pembahasan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $gambar = $soalDuplikat->gambar;
                $newGambar = null;
                if ($gambar && Storage::disk('public')->exists('soal/' . $gambar)) {
                    $newGambar = 'copy--' . time() . '-' . $gambar;
                    $path = 'soal/' . $newGambar;
                    Storage::disk('public')->copy('soal/' . $gambar, $path);

                    array_push($deletedImages, $path);
                }
                $savedDataQuestion['gambar'] = $newGambar;

                array_push($savedDataQuestions, $savedDataQuestion);
            }

            $saveQuestions = SoalUjian::insert($savedDataQuestions);
            if (!$saveQuestions) {
                throw new Exception('Gagal menduplikat Soal');
            }

            $logs = Auth::user()->name . ' telah menduplikasi produk tryout dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

            DB::commit();

            return Redirect::route('tryouts.index')->with('message', 'Produk tryout berhasil diduplikasi !');
        } catch (\Throwable $th) {
            foreach ($deletedImages as $image) {
                Storage::disk('public')->delete($image);
            }
            DB::rollback();

            dd($th->getMessage());

            return redirect()->back()->with('error', 'Gagal menduplikasi Produk !' . $th->getMessage());
        }
    }

    public function pesertaTryout(Request $request)
    {
        $hasilUjian = DB::table('hasil_ujian')
            ->select(
                'hasil_ujian.id',
                'hasil_ujian.durasi_selesai',
                'hasil_ujian.benar',
                'hasil_ujian.salah',
                'hasil_ujian.terjawab',
                'hasil_ujian.tidak_terjawab',
                'hasil_ujian.total_nilai as skd',
                'hasil_ujian.keterangan',
                'ujian.id as ujianID',
                'ujian.waktu_mulai',
                'ujian.sisa_waktu',
                'ujian.status_ujian',
                'order_tryout.customer_id',
                'order_tryout.produk_tryout_id',
                'customer.nama_lengkap',
                'produk_tryout.kategori_produk_id',
                'produk_tryout.nama_tryout',
                'kategori_produk.judul'
            )
            ->leftJoin('ujian', 'hasil_ujian.ujian_id', '=', 'ujian.id')
            ->rightJoin('order_tryout', 'ujian.order_tryout_id', '=', 'order_tryout.id')
            ->leftJoin('customer', 'order_tryout.customer_id', '=', 'customer.id')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('ujian.status_ujian', 'Selesai');

        if ($request->filled('kategori')) {
            $hasilUjian->where('kategori_produk.judul', $request->kategori)->orderBy('ujian.waktu_mulai', 'DESC');
        }

        if ($request->filled('jenisTryout')) {
            if ($request->input('jenisTryout') == 'Berbayar') {
                $hasilUjian = DB::table('hasil_ujian')
                    ->select(
                        'hasil_ujian.id',
                        'hasil_ujian.durasi_selesai',
                        'hasil_ujian.benar',
                        'hasil_ujian.salah',
                        'hasil_ujian.terjawab',
                        'hasil_ujian.tidak_terjawab',
                        'hasil_ujian.total_nilai as skd',
                        'hasil_ujian.keterangan',
                        'ujian.id as ujianID',
                        'ujian.waktu_mulai',
                        'ujian.sisa_waktu',
                        'ujian.status_ujian',
                        'order_tryout.customer_id',
                        'order_tryout.produk_tryout_id',
                        'customer.nama_lengkap',
                        'produk_tryout.kategori_produk_id',
                        'produk_tryout.nama_tryout',
                        'kategori_produk.judul'
                    )
                    ->leftJoin('ujian', 'hasil_ujian.ujian_id', '=', 'ujian.id')
                    ->rightJoin('order_tryout', 'ujian.order_tryout_id', '=', 'order_tryout.id')
                    ->leftJoin('customer', 'order_tryout.customer_id', '=', 'customer.id')
                    ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
                    ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                    ->where('ujian.status_ujian', 'Selesai')->orderBy('ujian.waktu_mulai', 'DESC');
            } else {
                $hasilUjian = DB::table('hasil_ujian')
                    ->select(
                        'hasil_ujian.id',
                        'hasil_ujian.durasi_selesai',
                        'hasil_ujian.benar',
                        'hasil_ujian.salah',
                        'hasil_ujian.terjawab',
                        'hasil_ujian.tidak_terjawab',
                        'hasil_ujian.total_nilai as skd',
                        'hasil_ujian.keterangan',
                        'ujian.id as ujianID',
                        'ujian.waktu_mulai',
                        'ujian.sisa_waktu',
                        'ujian.status_ujian',
                        'limit_tryout.customer_id',
                        'limit_tryout.produk_tryout_id',
                        'customer.nama_lengkap',
                        'produk_tryout.kategori_produk_id',
                        'produk_tryout.nama_tryout',
                        'kategori_produk.judul'
                    )
                    ->leftJoin('ujian', 'hasil_ujian.ujian_id', '=', 'ujian.id')
                    ->rightJoin('limit_tryout', 'ujian.limit_tryout_id', '=', 'limit_tryout.id')
                    ->leftJoin('customer', 'limit_tryout.customer_id', '=', 'customer.id')
                    ->leftJoin('produk_tryout', 'limit_tryout.produk_tryout_id', '=', 'produk_tryout.id')
                    ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')
                    ->where('ujian.status_ujian', 'Selesai')->orderBy('ujian.waktu_mulai', 'DESC');
            }
        }

        if ($request->filled('tahun')) {
            $hasilUjian->whereYear('ujian.waktu_mulai', $request->tahun)->orderBy('ujian.waktu_mulai', 'DESC');
        }

        $hasilUjian = $hasilUjian->paginate(10);

        $data = [
            'page_title' => 'Peserta Tryout',
            'bc1' => 'Manajemen Produk',
            'bc2' => 'Peserta Tryout',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'pesertaTryout' => $hasilUjian,
        ];
        return view('main-panel.tryout.data-peserta-tryout', $data);
    }

    public function tryoutGratis()
    {
        $permohonan = DB::table('limit_tryout')->select('limit_tryout.*', 'customer.nama_lengkap', 'produk_tryout.nama_tryout', 'kategori_produk.status')
            ->leftJoin('customer', 'limit_tryout.customer_id', '=', 'customer.id')
            ->leftJoin('produk_tryout', 'limit_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')->get();
        $data = [
            'page_title' => 'Tryout Gratis',
            'bc1' => 'Manajemen Produk',
            'bc2' => 'Pengajuan Tryout Gratis',
            'permohonanTryout' => $permohonan,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.tryout.data-pengajuan-tryout-gratis', $data);
    }

    public function validasiTryoutGratis(Request $request): RedirectResponse
    {
        // Cek Permohonan
        $permohonan = LimitTryout::findOrFail(Crypt::decrypt($request->id));
        $permohonan->status_validasi = htmlspecialchars($request->input('validasi'));
        $permohonan->validasi_oleh = Auth::user()->id;
        $emailCustomer = User::where('customer_id', $permohonan->customer_id)->first();
        $email = $emailCustomer->email;
        if ($permohonan->save()) {
            // Simpan logs aktivitas pengguna
            $logs = Auth::user()->name . ' telah memvalidasi permohonan tryout gratis dengan ID ' . Crypt::decrypt($request->id) . 'aksi validasi :' . htmlspecialchars($request->input('validasi')) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

            if (htmlspecialchars($request->input('validasi')) == 'Disetujui') {
                // Job send email notification ke user , tryout gratis disetujui !
                SendAcceptTryoutGratisJob::dispatch($email);
                $message = 'Validasi berhasil disetujui !';
            } elseif (htmlspecialchars($request->input('validasi')) == 'Ditolak') {
                // Job send email notification ke user , tryout gratis ditolak !
                SendDeniedTryoutGratisJob::dispatch($email);
                $message = 'Validasi berhasil ditolak !';
            }
            return Redirect::route('tryouts.pengajuan-tryout-gratis')->with('message', $message);
        } else {
            return Redirect::route('tryouts.pengajuan-tryout-gratis')->with('error', 'Validasi berhasil !');
        }
    }

    // public function email()
    // {
    //     // return view('main-panel.tryout.tryout-gratis-email');

    //     $permohonan = LimitTryout::findOrFail('93318');
    //     $emailCustomer = User::where('customer_id', $permohonan->customer_id)->first();
    //     $tryout = ProdukTryout::find($permohonan->produk_tryout_id)->first();

    //     // Kirim email invoice
    //     Mail::to($emailCustomer->email)->send(new EmailValidasiTryoutGratis($tryout));
    //     return response()->json(['message' => 'Pemesanan berhasil dan email invoice telah dikirim']);
    // }
}
