<?php

namespace App\Http\Controllers\Exam;

use App\Enums\ReportExamStatus;
use App\Helpers\Notifikasi;
use App\Helpers\QueryCollect;
use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReportExamRequest;
use App\Models\LimitTryout;
use App\Models\ReportExamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ReportExamController extends Controller
{
    public function examTrouble(Request $request)
    {
        $status = $request->status;

        $statusList = ReportExamModel::getStatusList();

        if ($status) {
            if (!array_key_exists($status, $statusList)) {
                return redirect()->back()->with('error', 'Status Laporan tidak dikenali');
            }
        }

        $data = [
            'form_title' => 'Laporan Kendala Ujian',
            'page_title' => 'Laporan Kendala Ujian',
            'bc1' => 'Dashboard',
            'bc2' => 'Manajemen Ujian',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'reportExam' => QueryCollect::reportExam($status)->get(),
            'status' => $status,
            'statusList' => $statusList,
        ];

        return view('main-panel.ujian.data-kendala-ujian', $data);
    }

    public function sendReportExam(ReportExamRequest $request)
    {
        $request->validated();

        // Check if already report
        $checkExists = ReportExamModel::where('user_id', Auth::id())
            ->where('soal_id', $request->input('idSoal'))
            ->first();
        if ($checkExists) {
            return response()->json(['result' => 'error', 'title' => 'Anda sudah melaporkan soal ini.']);
        }

        $fileScreenshot = $request->file('screenshot');
        $fileHashname = $fileScreenshot->hashName();

        // Upload screenshot report ujian
        if (!$fileScreenshot) {
            return response()->json(['result' => 'error', 'title' => 'Silahkan masukkan Screenshot!']);
        }

        $folder = 'public/ujian';
        if (!Storage::exists($folder)) {
            Storage::makeDirectory($folder);
        }

        $fileUpload = $fileScreenshot->storeAs($folder, $fileHashname);
        if (!$fileUpload) {
            return response()->json(['result' => 'error', 'title' => 'Unggah screenshot gagal !']);
        }

        $reportExam = ReportExamModel::create([
            'user_id' => Auth::id(),
            'produk_tryout_id' => $request->input('idProduk'),
            'soal_id' => $request->input('idSoal'),
            'deskripsi' => htmlspecialchars($request->input('deskripsi')),
            'screenshot' => $fileHashname,
            'status' => ReportExamStatus::WAITING->value,
        ]);

        if (!$reportExam) {
            return response()->json(['result' => 'error', 'title' => 'Laporan gagal dikirim !']);
        }

        return response()->json(['result' => 'success', 'title' => 'Laporan berhasil dikirim !']);
    }

    public function validatedReportExam(Request $request)
    {
        $reportExam = ReportExamModel::findOrFail(Crypt::decrypt($request->id));

        $statusList = ReportExamModel::getStatusList();
        $newStatus = ReportExamStatus::FIXED->value;
        if ($reportExam->status === ReportExamStatus::FIXED->value) {
            $newStatus = ReportExamStatus::WAITING->value;
        }

        $save = $reportExam->update([
            'status' => $newStatus,
        ]);

        if (!$save) {
            return redirect()->back()->with('error', 'Gagal memvalidasi laporan ujian !');
        }

        $message = 'Validasi laporan ujian berhasil diperbarui menjadi ' . $statusList[$newStatus] . ' !';
        $logs = Auth::user()->name . ' telah mengubah status laporan ujian dengan ID ' . Crypt::decrypt($request->id) . ' menjadi ' . $statusList[$newStatus] . ' waktu tercatat :  ' . now();

        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->back()->with('message', $message);
    }

    public function deleteReportExam(Request $request)
    {
        $reportExam = ReportExamModel::findOrFail(Crypt::decrypt($request->id));
        if (!$reportExam) {
            return redirect()->back()->with('error', 'Laporan Ujian tidak ditemukan !');
        }
        $screenshot = $reportExam->screenshot;

        $delete = $reportExam->delete();
        if (!$delete) {
            return redirect()->back()->with('error', 'Laporan Ujian gagal dihapus !');
        }

        // Delete screenshot file
        Storage::disk('public')->delete('ujian/' . $screenshot);

        // Simpan logs aktivitas pengguna
        $logs = Auth::user()->name . ' telah menghapus laporan ujian dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->back()->with('message', 'Laporan ujian berhasil dihapus !');
    }
}
