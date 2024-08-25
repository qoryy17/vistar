<?php

namespace App\Http\Controllers\Panel;

use App\Models\User;
use App\Models\SoalUjian;
use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Support\Str;
use App\Models\ProdukTryout;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use App\Models\KlasifikasiSoal;
use App\Models\PengaturanTryout;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use App\Mail\EmailValidasiTryoutGratis;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Panel\SoalRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Panel\ProdukTryoutRequest;

class Tryouts extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Produk Tryout',
            'bc1' => 'Manajemen Tryout',
            'bc2' => 'Produk Tryout',
            'tryouts' => DB::table('produk_tryout')->select('produk_tryout.*', 'pengaturan_tryout.harga', 'pengaturan_tryout.harga_promo', 'pengaturan_tryout.passing_grade', 'kategori_produk.judul', 'kategori_produk.status as produk_status')->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')->leftJoin('kategori_produk', 'produk_tryout.kategori_produk_id', '=', 'kategori_produk.id')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
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
            return Redirect::to('/produk-tryout')->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $form_title,
            'bc1' => 'Manajemen Tryout',
            'bc2' => 'Produk Tryout',
            'kategori' => KategoriProduk::all()->where('aktif', 'Y'),
            'tryout' => $tryout,
            'pengaturan' => $pengaturan,
            'formParam' => $formParam,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.tryout.form-data-produk-tryout', $data);
    }

    function simpanProdukTryout(ProdukTryoutRequest $request): RedirectResponse
    {
        // Autentifikasi user
        $users = Auth::user();
        // Validasi inputan
        $request->validated();
        if (Crypt::decrypt($request->input('formParameter')) == 'add') {

            // Upload thumbnail produk tryout
            $fileThumbnail = $request->file('thumbnail');
            $fileHashname = $fileThumbnail->hashName();

            $fileUpload = $fileThumbnail->storeAs('public\tryout', $fileHashname);
            if ($fileUpload) {

                $pengaturanID = rand(1, 999) . rand(1, 99);

                $pengaturanTryout = new PengaturanTryout();
                $pengaturanTryout->id = $pengaturanID;
                $pengaturanTryout->harga = intval(htmlspecialchars($request->input('harga')));
                $pengaturanTryout->harga_promo = intval(htmlspecialchars($request->input('hargaPromo')));
                $pengaturanTryout->durasi = intval(htmlspecialchars($request->input('durasiUjian')));
                if (!$request->input('nilaiKeluar')) {
                    $pengaturanTryout->nilai_keluar = 'T';
                } else {
                    $pengaturanTryout->nilai_keluar = htmlspecialchars($request->input('nilaiKeluar'));
                }
                if (!$request->input('grafikEvaluasi')) {
                    $pengaturanTryout->grafik_evaluasi = 'T';
                } else {
                    $pengaturanTryout->grafik_evaluasi = htmlspecialchars($request->input('grafikEvaluasi'));
                }
                if (!$request->input('reviewPembahasan')) {
                    $pengaturanTryout->review_pembahasan = 'T';
                } else {
                    $pengaturanTryout->review_pembahasan = htmlspecialchars($request->input('reviewPembahasan'));
                }
                if (!$request->input('ulangUjian')) {
                    $pengaturanTryout->ulang_ujian = 'T';
                } else {
                    $pengaturanTryout->ulang_ujian = htmlspecialchars($request->input('ulangUjian'));
                }
                $pengaturanTryout->masa_aktif = intval(htmlspecialchars($request->input('masaAktif')));
                $pengaturanTryout->passing_grade = htmlspecialchars($request->input('passingGrade'));

                $produkTryout = new ProdukTryout();
                $produkTryout->id = rand(1, 999) . rand(1, 99);
                $produkTryout->nama_tryout = htmlspecialchars($request->input('namaTryout'));
                $produkTryout->keterangan = htmlspecialchars($request->input('keterangan'));
                $produkTryout->kode_soal = Str::random(5) . rand(1, 999);
                $produkTryout->pengaturan_tryout_id = $pengaturanID;
                $produkTryout->user_id = $users->id;
                $produkTryout->kategori_produk_id = htmlspecialchars($request->input('kategori'));
                $produkTryout->status = htmlspecialchars($request->input('status'));
                $produkTryout->thumbnail = $fileHashname;

                // Catatan log
                $logs = $users->name . ' telah menambahkan produk tryout ' . htmlspecialchars($request->input('nama')) . ' waktu tercatat :  ' . now();
                $message = 'Produk tryout berhasil disimpan !';
                $error = 'Produk tryout gagal disimpan !';
            } else {
                return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
            }
        } elseif (Crypt::decrypt($request->input('formParameter')) == 'update') {
            // Cari produk tryout berdasarkan produk id
            $produkTryout = ProdukTryout::findOrFail(htmlspecialchars($request->input('produkID')));
            $pengaturanTryout = PengaturanTryout::findOrFail($produkTryout->pengaturan_tryout_id);

            $pengaturanTryout->harga = intval(htmlspecialchars($request->input('harga')));
            $pengaturanTryout->harga_promo = intval(htmlspecialchars($request->input('hargaPromo')));
            $pengaturanTryout->durasi = intval(htmlspecialchars($request->input('durasiUjian')));
            if (!$request->input('nilaiKeluar')) {
                $pengaturanTryout->nilai_keluar = 'T';
            } else {
                $pengaturanTryout->nilai_keluar = htmlspecialchars($request->input('nilaiKeluar'));
            }
            if (!$request->input('grafikEvaluasi')) {
                $pengaturanTryout->grafik_evaluasi = 'T';
            } else {
                $pengaturanTryout->grafik_evaluasi = htmlspecialchars($request->input('grafikEvaluasi'));
            }
            if (!$request->input('reviewPembahasan')) {
                $pengaturanTryout->review_pembahasan = 'T';
            } else {
                $pengaturanTryout->review_pembahasan = htmlspecialchars($request->input('reviewPembahasan'));
            }
            if (!$request->input('ulangUjian')) {
                $pengaturanTryout->ulang_ujian = 'T';
            } else {
                $pengaturanTryout->ulang_ujian = htmlspecialchars($request->input('ulangUjian'));
            }
            $pengaturanTryout->masa_aktif = intval(htmlspecialchars($request->input('masaAktif')));
            $pengaturanTryout->passing_grade = htmlspecialchars($request->input('passingGrade'));

            $produkTryout->nama_tryout = htmlspecialchars($request->input('namaTryout'));
            $produkTryout->keterangan = htmlspecialchars($request->input('keterangan'));
            $produkTryout->kategori_produk_id = htmlspecialchars($request->input('kategori'));
            if ($request->hasFile('thumbnail')) {
                // Upload thumbnail produk tryout
                $fileThumbnail = $request->file('thumbnail');
                $fileHashname = $fileThumbnail->hashName();
                $fileUpload = $fileThumbnail->storeAs('public\tryout', $fileHashname);

                if (!$fileUpload) {
                    // Hapus thumbnail yang lama
                    Storage::disk('public')->delete('public\tryout' . $produkTryout->thumbnail);
                    $produkTryout->thumbnail = $fileHashname;
                } else {
                    return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
                }
            }
            $produkTryout->status = htmlspecialchars($request->input('status'));

            // Catatan log
            $logs = $users->name . ' telah memperbarui produk tryout dengan ID' . htmlspecialchars($request->input('produkID')) . ' waktu tercatat :  ' . now();
            $message = 'Produk tryout berhasil diperbarui !';
            $error = 'Produk tryout gagal diperbarui !';
        } else {
            return Redirect::to('/produk-tryout')->with('error', 'Parameter tidak valid !');
        }

        if ($pengaturanTryout->save() and $produkTryout->save()) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::to('/produk-tryout')->with('message', $message);
        } else {
            return Redirect::to('/produk-tryout')->with('error', $error)->withInput();
        }
    }

    public function hapusProdukTryout(Request $request): RedirectResponse
    {
        $produkTryout = ProdukTryout::findOrFail(Crypt::decrypt($request->id));
        if ($produkTryout) {
            $users = Auth::user();
            $pengaturanTryout = PengaturanTryout::findOrFail($produkTryout->pengaturan_tryout_id);

            if (!$pengaturanTryout) {
                return Redirect::to('/produk-tryout')->with('error', 'ID pengaturan tidak ditemukan !');
            }

            $soalUjian = DB::table('soal_ujian')->where('kode_soal', '=', $produkTryout->kode)->first();
            if (!$soalUjian) {
                return Redirect::to('/produk-tryout')->with('error', 'Soal tidak ditemukan !');
            }
            $produkTryout->delete();
            $pengaturanTryout->delete();
            DB::table('soal_ujian')->where('kode_soal', '=', $produkTryout->kode)->delete();
            // Simpan logs aktivitas pengguna
            $logs = $users->name . ' telah menghapus produk tryout dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::to('/produk-tryout')->with('message', 'Produk tryout berhasil dihapus !');
        }
        return Redirect::to('/produk-tryout')->with('error', 'Produk tryout gagal dihapus !');
    }

    public function soalTryout($id = null)
    {
        $data = [
            'page_title' => 'Kelola Soal',
            'bc1' => 'Manajemen Produk',
            'bc2' => 'Produk Tryout',
            'kode_soal' => $id,
            'soal' => DB::table('soal_ujian')->select('soal_ujian.*', 'klasifikasi_soal.judul', 'klasifikasi_soal.alias', 'klasifikasi_soal.passing_grade')->leftJoin('klasifikasi_soal', 'klasifikasi_soal.id', '=', 'soal_ujian.klasifikasi_soal_id')->where('soal_ujian.kode_soal', '=', Crypt::decrypt($id))->orderBy('soal_ujian.updated_at', 'DESC')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.tryout.data-soal-tryout', $data);
    }

    public function formSoalTryout($param = null, $id = null, $soal = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Soal Tryout';
            $formParam = Crypt::encrypt('add');
            $soal = '';
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Soal Tryout';
            $formParam = Crypt::encrypt('update');
            $soal = DB::table('soal_ujian')->select('soal_ujian.*', 'klasifikasi_soal.judul', 'klasifikasi_soal.alias')->leftJoin('klasifikasi_soal', 'klasifikasi_soal.id', '=', 'soal_ujian.klasifikasi_soal_id')->where('soal_ujian.id', '=', Crypt::decrypt($soal))->get();
        } else {
            return Redirect::to('/soal-tryout')->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $form_title,
            'bc1' => 'Kelola Soal',
            'bc2' => $form_title,
            'kode_soal' => $id,
            'soal' => $soal,
            'formParam' => $formParam,
            'klasifikasi_soal' => KlasifikasiSoal::all()->whereNotIn('aktif', 'T'),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.tryout.form-data-soal-tryout', $data);
    }

    public function simpanSoalUjian(SoalRequest $request): RedirectResponse
    {
        // Autentifikasi user
        $users = Auth::user();
        // Validasi inputan
        $request->validated();

        if (Crypt::decrypt($request->input('formParameter')) == 'add') {

            $soal = new SoalUjian();

            $idSoal = rand(1, 999) . rand(1, 99);
            $soal->id = $idSoal;
            $soal->kode_soal = htmlspecialchars(Crypt::decrypt($request->input('kodeSoal')));
            $soal->soal = $request->input('soal');

            if ($request->hasFile('gambar')) {
                $fileSoalGambar = $request->file('gambar');
                $fileHashname = $fileSoalGambar->hashName();

                $fileUpload = $fileSoalGambar->storeAs('public\soal', $fileHashname);
                if (!$fileUpload) {
                    return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
                }
                $soal->gambar = $fileHashname;
            }
            $soal->jawaban_a = $request->input('jawabanA');
            $soal->jawaban_a = $request->input('jawabanA');
            $soal->jawaban_b = $request->input('jawabanB');
            $soal->jawaban_c = $request->input('jawabanC');
            $soal->jawaban_d = $request->input('jawabanD');
            $soal->kunci_jawaban = $request->input('kunciJawaban');
            $soal->klasifikasi_soal_id = htmlspecialchars($request->input('klasifikasi'));
            $soal->review_pembahasan = $request->input('reviewPembahasan');
            $soal->poin = htmlspecialchars($request->input('poin'));

            // Catatan log
            $logs = $users->name . ' telah menambahkan soal ' . $idSoal . ' waktu tercatat :  ' . now();
            $message = 'Soal ujian berhasil disimpan !';
            $error = 'Soal ujian gagal disimpan !';
        } elseif (Crypt::decrypt($request->input('formParameter')) == 'update') {

            $soal = SoalUjian::findOrFail(htmlspecialchars(Crypt::decrypt($request->input('idSoal'))));

            $soal->soal = $request->input('soal');
            if ($request->hasFile('gambar')) {
                // Upload gambar produk tryout
                $fileSoalGambar = $request->file('gambar');
                $fileHashname = $fileSoalGambar->hashName();
                $fileUpload = $fileSoalGambar->storeAs('public\soal', $fileHashname);

                if (!$fileUpload) {
                    return back()->with('error', 'Unggah soal gambar gagal !')->withInput();
                }
                // Hapus gambar yang lama
                Storage::disk('public')->delete('public/soal' . $soal->gambar);
                $soal->gambar = $fileHashname;
            }
            $soal->jawaban_a = $request->input('jawabanA');
            $soal->jawaban_a = $request->input('jawabanA');
            $soal->jawaban_b = $request->input('jawabanB');
            $soal->jawaban_c = $request->input('jawabanC');
            $soal->jawaban_d = $request->input('jawabanD');
            $soal->kunci_jawaban = $request->input('kunciJawaban');
            $soal->klasifikasi_soal_id = htmlspecialchars($request->input('klasifikasi'));
            $soal->review_pembahasan = $request->input('reviewPembahasan');
            $soal->poin = htmlspecialchars($request->input('poin'));
            // Catatan log
            $logs = $users->name . ' telah memperbarui soal dengan ID ' . $soal->id . ' waktu tercatat :  ' . now();
            $message = 'Soal ujian berhasil diperbarui !';
            $error = 'Soal ujian gagal diperbarui !';
        } else {
            return Redirect::to('/soal-tryout')->with('error', 'Parameter tidak valid !');
        }

        if ($soal->save()) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::to('/soal-tryout' . '/' . Crypt::encrypt($request->input('kodeSoal')))->with('message', $message);
        } else {
            return Redirect::to('/soal-tryout' . '/' . Crypt::encrypt($request->input('kodeSoal')))->with('error', $error)->withInput();
        }
    }

    public function hapusSoalUjian(Request $request): RedirectResponse
    {
        $soalUjian = soalUjian::findOrFail(Crypt::decrypt($request->id));
        if ($soalUjian) {
            $users = Auth::user();
            $soalUjian->delete();
            // Simpan logs aktivitas pengguna
            $logs = $users->name . ' telah menghapus soal ujian dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::to('/soal-tryout' . '/' . Crypt::encrypt($soalUjian->kode_soal))->with('message', 'Soal berhasil dihapus !');
        }
        return Redirect::to('/soal-tryout' . '/' . Crypt::encrypt($soalUjian->kode_soal))->with('error', 'Soal gagal dihapus !');
    }

    public function duplikatProdukTryout(Request $request): RedirectResponse
    {
        $produkTryout = ProdukTryout::findOrFail(Crypt::decrypt($request->id));

        $users = Auth::user();
        $kodeSoalGenerate = Str::random(5) . rand(1, 999);
        $idPengaturanGenerate = rand(1, 999) . rand(1, 99);

        $fileThumbnail = 'copy--' . $produkTryout->thumbnail;

        $pengaturanTryout = PengaturanTryout::findOrFail($produkTryout->pengaturan_tryout_id);
        DB::table('pengaturan_tryout')->insert([
            'id' => $idPengaturanGenerate,
            'harga' => $pengaturanTryout->harga,
            'harga_promo' => $pengaturanTryout->harga_promo,
            'durasi' => $pengaturanTryout->durasi,
            'nilai_keluar' => $pengaturanTryout->nilai_keluar,
            'grafik_evaluasi' => $pengaturanTryout->grafik_evaluasi,
            'review_pembahasan' => $pengaturanTryout->review_pembahasan,
            'ulang_ujian' => $pengaturanTryout->ulang_ujian,
            'masa_aktif' => $pengaturanTryout->masa_aktif,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('produk_tryout')->insert([
            'id' => rand(1, 999) . rand(1, 99),
            'nama_tryout' => $produkTryout->nama_tryout,
            'keterangan' => $produkTryout->keterangan,
            'kode_soal' => $kodeSoalGenerate,
            'pengaturan_tryout_id' => $idPengaturanGenerate,
            'user_id' => $users->id,
            'kategori_produk_id' => $produkTryout->kategori_produk_id,
            'status' => 'Tidak Tersedia',
            'thumbnail' => $fileThumbnail,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        Storage::disk('public')->copy('tryout/' . $produkTryout->thumbnail, 'tryout/' . $fileThumbnail);

        $soal = DB::table('soal_ujian')->where('kode_soal', '=', $produkTryout->kode_soal)->get();
        foreach ($soal as $soalDuplikat) {
            $fileGambar = 'copy--' . $soalDuplikat->gambar;
            if ($soalDuplikat->gambar == null) {
                DB::table('soal_ujian')->insert([
                    'id' => rand(1, 999) . rand(1, 99),
                    'kode_soal' => $kodeSoalGenerate,
                    'soal' => $soalDuplikat->soal,
                    'jawaban_a' => $soalDuplikat->jawaban_a,
                    'jawaban_b' => $soalDuplikat->jawaban_b,
                    'jawaban_c' => $soalDuplikat->jawaban_c,
                    'jawaban_d' => $soalDuplikat->jawaban_d,
                    'jawaban_e' => $soalDuplikat->jawaban_e,
                    'kunci_jawaban' => $soalDuplikat->kunci_jawaban,
                    'klasifikasi_soal_id' => $soalDuplikat->klasifikasi_soal_id,
                    'review_pembahasan' => $soalDuplikat->review_pembahasan,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                DB::table('soal_ujian')->insert([
                    'id' => rand(1, 999) . rand(1, 99),
                    'kode_soal' => $kodeSoalGenerate,
                    'soal' => $soalDuplikat->soal,
                    'gambar' => $fileGambar,
                    'jawaban_a' => $soalDuplikat->jawaban_a,
                    'jawaban_b' => $soalDuplikat->jawaban_b,
                    'jawaban_c' => $soalDuplikat->jawaban_c,
                    'jawaban_d' => $soalDuplikat->jawaban_d,
                    'jawaban_e' => $soalDuplikat->jawaban_e,
                    'kunci_jawaban' => $soalDuplikat->kunci_jawaban,
                    'klasifikasi_soal_id' => $soalDuplikat->klasifikasi_soal_id,
                    'review_pembahasan' => $soalDuplikat->review_pembahasan,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                Storage::disk('public')->copy('soal/' . $soalDuplikat->gambar, 'soal/' . $fileGambar);
            }
        }

        if (!$soal) {
            return Redirect::to('/produk-tryout')->with('error', 'Produk tryout gagal diduplikasi !');
        }
        $logs = Auth::user()->name . ' telah menduplikasi produk tryout dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return Redirect::to('/produk-tryout')->with('message', 'Produk tryout berhasil diduplikasi !');
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
            $hasilUjian->where('kategori_produk.judul', $request->kategori);
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
                    ->where('ujian.status_ujian', 'Selesai');
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
                    ->where('ujian.status_ujian', 'Selesai');
            }
        }

        if ($request->filled('tahun')) {
            $hasilUjian->whereYear('ujian.waktu_mulai', $request->tahun);
        }

        $hasilUjian = $hasilUjian->paginate(10);

        $data = [
            'page_title' => 'Peserta Tryout',
            'bc1' => 'Manajemen Produk',
            'bc2' => 'Peserta Tryout',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'pesertaTryout' => $hasilUjian
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
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.tryout.data-pengajuan-tryout-gratis', $data);
    }

    public function validasiTryoutGratis(Request $request) //: RedirectResponse
    {
        // Cek Permohonan
        $permohonan = LimitTryout::findOrFail(Crypt::decrypt($request->id));
        $permohonan->status_validasi = htmlspecialchars($request->input('validasi'));
        $emailCustomer = User::where('customer_id', $permohonan->customer_id)->first();
        $tryout = ProdukTryout::find($permohonan->produk_tryout_id)->first();

        if ($permohonan->save()) {
            // Simpan logs aktivitas pengguna
            $logs = Auth::user()->name . ' telah memvalidasi permohonan tryout gratis dengan ID ' . Crypt::decrypt($request->id) . 'aksi validasi :' . htmlspecialchars($request->input('validasi')) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

            if (htmlspecialchars($request->input('validasi')) == 'Disetujui') {
                // Kirim email invoice
                Mail::to($emailCustomer->email)->send(new EmailValidasiTryoutGratis($tryout));
            }
            return Redirect::route('tryouts.pengajuan-tryout-gratis')->with('message', 'Validasi berhasil !');
        } else {
            return Redirect::route('tryouts.pengajuan-tryout-gratis')->with('error', 'Validasi gagal berhasil !');
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
