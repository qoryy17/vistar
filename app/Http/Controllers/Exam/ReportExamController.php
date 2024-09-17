<?php

namespace App\Http\Controllers\Exam;

use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use App\Helpers\QueryCollect;
use App\Models\ReportExamModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Customer\ReportExamRequest;

class ReportExamController extends Controller
{
    public function examTrouble()
    {
        $data = [
            'form_title' => 'Laporan Kendala Ujian',
            'page_title' => 'Laporan Kendala Ujian',
            'bc1' => 'Dashboard',
            'bc2' => 'Manajemen Ujian',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'reportExam' => QueryCollect::reportExam()->get()
        ];

        return view('main-panel.ujian.data-kendala-ujian', $data);
    }

    public function sendReportExam(ReportExamRequest $request)
    {
        $request->validated();

        $fileScreenshot = $request->file('screenshot');
        $fileHashname = $fileScreenshot->hashName();

        // Upload screenshot report ujian
        if (!$request->file('screenshot')) {
            return redirect()->back()->with('error', 'Screenshot belum di unggah ulang !')->withInput();
        }

        $fileUpload = $fileScreenshot->storeAs('public/ujian', $fileHashname);
        if (!$fileUpload) {
            return back()->with('error', 'Unggah screenshot gagal !')->withInput();
        }

        $reportExam = new ReportExamModel();
        $reportExam->produk_tryout_id = $request->input('idProduk');
        $reportExam->soal_id = $request->input('idSoal');
        $reportExam->deskripsi = htmlspecialchars($request->input('deskripsi'));
        $reportExam->screenshot = $fileHashname;
        $reportExam->status = 'Waiting';

        if ($reportExam->save()) {
            return response()->json(['message' => 'Laporan berhasil dikirim !']);
        } else {
            return response()->json(['message' => 'Laporan gagal dikirim !']);
        }
    }

    public function validatedReportExam(Request $request)
    {
        $reportExam = ReportExamModel::findOrFail(Crypt::decrypt($request->id));
        $users = Auth::user();
        if ($reportExam->status == 'Waiting') {
            $reportExam->status = 'Fixed';
            $logs = $users->name . ' telah menyelesaikan report ujian dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            $message = 'Validasi report ujian berhasil diset fixed !';
        } elseif ($reportExam->status == 'Fixed') {
            $reportExam->status = 'Waiting';
            $logs = $users->name . ' telah membatalkan penyelesaian report ujian dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            $message = 'Validasi report ujian berhasil diset waiting !';
        } else {
            return redirect()->back()->with('error', 'Status report ujian tidak valid !');
        }
        if ($reportExam->save()) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('report.exams')->with('message', $message);
        } else {
            return Redirect::route('report.exams')->with('error', 'Gagal memvalidasi report ujian !');
        }
    }

    public function deleteReportExam(Request $request)
    {
        $reportExam = ReportExamModel::findOrFail(Crypt::decrypt($request->id));
        if ($reportExam) {

            Storage::disk('public')->delete('ujian/' . $reportExam->screenshot);

            $users = Auth::user();
            $reportExam->delete();
            // Simpan logs aktivitas pengguna
            $logs = $users->name . ' telah menghapus report ujian dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('report.exams')->with('message', 'Report ujian berhasil dihapus !');
        }
        return Redirect::route('report.exams')->with('error', 'Report ujian gagal dihapus !');
    }
}
