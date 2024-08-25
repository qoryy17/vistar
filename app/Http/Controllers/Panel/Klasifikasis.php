<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use App\Models\KlasifikasiSoal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Panel\KlasifikasiSoalRequest;

class Klasifikasis extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Klasifikasi Soal',
            'bc1' => 'Manajemen Tryout',
            'bc2' => 'Klasifikasi Soal Tryout',
            'klasifikasi' => KlasifikasiSoal::all(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.klasifikasi-soal.data-klasifikasi-soal', $data);
    }

    public function formKlasifikasiSoal($param = null, $id = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Klasifikasi';
            $klasifikasi = '';
            $formParam = Crypt::encrypt('add');
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Klasifikasi';
            $klasifikasi = KlasifikasiSoal::findOrFail(Crypt::decrypt($id));
            $formParam = Crypt::encrypt('update');
        } else {
            return Redirect::back()->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $form_title,
            'bc1' => 'Manajemen Klasifikasi Soal',
            'bc2' => $form_title,
            'klasifikasi' => $klasifikasi,
            'formParam' => $formParam,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
        ];
        return view('main-panel.klasifikasi-soal.form-klasifikasi-soal', $data);
    }

    public function simpanKlasifikasiSoal(KlasifikasiSoalRequest $request): RedirectResponse
    {
        // Autentifikasi user
        $users = Auth::user();
        // Validasi inputan
        $request->validated();

        if (Crypt::decrypt($request->input('formParameter')) == 'add') {
            $klasifikasiSoal = new KlasifikasiSoal();
            $klasifikasiSoal->id = rand(1, 999) . rand(1, 99);
            $klasifikasiSoal->judul = ucwords(htmlspecialchars($request->input('judul')));
            $klasifikasiSoal->alias = strtoupper(htmlspecialchars($request->input('alias')));
            $klasifikasiSoal->passingGrade = strtoupper(htmlspecialchars($request->input('passingGrade')));
            $klasifikasiSoal->aktif = htmlspecialchars($request->input('aktif'));

            // Catatan logs
            $logs = $users->name . ' telah menambahkan klasifikasi soal ' . $request->input('judul') . ' waktu tercatat :  ' . now();
            $message = 'Klasifikasi soal berhasil disimpan !';
            $error = 'Klasifikasi soal gagal disimpan !';
        } elseif (Crypt::decrypt($request->input('formParameter')) == 'update') {
            $klasifikasiSoal = KlasifikasiSoal::findOrFail($request->input('klasifikasiID'));
            $klasifikasiSoal->judul = ucwords(htmlspecialchars($request->input('judul')));
            $klasifikasiSoal->alias = strtoupper(htmlspecialchars($request->input('alias')));
            $klasifikasiSoal->passingGrade = strtoupper(htmlspecialchars($request->input('passingGrade')));
            $klasifikasiSoal->aktif = htmlspecialchars($request->input('aktif'));

            // Catatan logs
            $logs = $users->name . ' telah memperbarui klasifikasi soal dengan ID' . $request->input('klasifikasiID') . ' waktu tercatat :  ' . now();
            $message = 'Klasifikasi soal berhasil diperbarui !';
            $error = 'Klasifikasi soal gagal diperbarui !';
        } else {
            return Redirect::to('/klasifikasi-soal')->with('error', 'Parameter tidak valid !');
        }

        if ($klasifikasiSoal->save()) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::to('/klasifikasi-soal')->with('message', $message);
        } else {
            return Redirect::to('/klasifikasi-soal')->with('error', $error)->withInput();
        }
    }

    public function ubahAktifKlasifikasi(Request $request): RedirectResponse
    {
        $klasifikasiSoal = KlasifikasiSoal::findOrFail(Crypt::decrypt($request->id));
        if ($klasifikasiSoal) {
            $users = Auth::user();
            if ($klasifikasiSoal->aktif == 'Y') {
                $klasifikasiSoal->aktif = 'T';
                $klasifikasiSoal->save();
                // Simpan logs aktivitas pengguna
                $logs = $users->name . ' telah menonaktifkan klasifikasi soal dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::to('/klasifikasi-soal')->with('message', 'Klasifikasi soal berhasil dinonaktifkan !');
            } else {
                $klasifikasiSoal->aktif = 'Y';
                $klasifikasiSoal->save();
                // Simpan logs aktivitas pengguna
                $logs = $users->name . ' telah mengaktifkan klasifikasi soal dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::to('/klasifikasi-soal')->with('message', 'Klasifikasi soal berhasil diaktifkan !');
            }
        }
        return Redirect::to('/klasifikasi-soal')->with('error', 'Klasifikasi Soal gagal diubah !');
    }

    public function hapusKlasifikasi(Request $request): RedirectResponse
    {
        $klasifikasiSoal = KlasifikasiSoal::findOrFail(Crypt::decrypt($request->id));
        if ($klasifikasiSoal) {
            $users = Auth::user();
            $klasifikasiSoal->delete();
            // Simpan logs aktivitas pengguna
            $logs = $users->name . ' telah menghapus klasifikasi soal dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::to('/klasifikasi-soal')->with('message', 'Klasifikasi soal berhasil dihapus !');
        }
        return Redirect::to('/klasifikasi-soal')->with('error', 'Klasifikasi soal gagal dihapus !');
    }
}
