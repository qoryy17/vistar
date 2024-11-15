<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\BerandaUI;
use App\Helpers\Notifikasi;
use App\Helpers\Waktu;
use App\Http\Controllers\Controller;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Main extends Controller
{

    public function index()
    {
        $data = [
            'page_title' => Auth::user()->name,
            'sesiWaktu' => Waktu::sesiWaktu(),
            'breadcumb' => 'Beranda Vistar Indonesia',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'countSertikomTraining' => BerandaUI::statistikSertikom('Pelatihan'),
            'countSertikomSeminar' => BerandaUI::statistikSertikom('Seminar'),
            'countSertikomWorkshop' => BerandaUI::statistikSertikom('Workshop'),
            'countStatistikCPNS' => BerandaUI::statistikTryout('CPNS'),
            'countStatistikPPPK' => BerandaUI::statistikTryout('PPPK'),
            'countStatistikKedinasan' => BerandaUI::statistikTryout('Kedinasan'),
            'countCustomer' => BerandaUI::customerTerdaftar(),
            'countCustomerPerhari' => BerandaUI::customerTerdaftarPerhari(),
            'countTryout' => BerandaUI::tryoutTerjual(),
            'countTryoutPerhari' => BerandaUI::tryoutTerjualPerhari(),
            'sumTryoutPaid' => BerandaUI::sumTryoutPaid(),
            'sumTryoutPerhariPaid' => BerandaUI::sumTryoutPerhariPaid(),
            'sumTryoutPending' => BerandaUI::sumTryoutPending(),
            'sumTryoutPerhariPending' => BerandaUI::sumTryoutPerhariPending(),
            'waitingReportExam' => BerandaUI::reportExam()->get()
        ];

        return view('main-panel.home.beranda', $data);
    }

    public function getChart(Request $request)
    {
        $year = $request->year;
        $data = [
            'CPNS' => BerandaUI::countPenjualan('CPNS', $year),
            'PPPK' => BerandaUI::countPenjualan('PPPK', $year),
            'Kedinasan' => BerandaUI::countPenjualan('Kedinasan', $year),
            'Pelatihan' => BerandaUI::countOrderSertikom('Pelatihan', $year),
            'Seminar' => BerandaUI::countOrderSertikom('Seminar', $year),
            'Workshop' => BerandaUI::countOrderSertikom('Workshop', $year),
        ];

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            foreach ($data as $key => $values) {
                if (!isset($values[$i])) {
                    $data[$key][$i] = 0;
                }
            }
        }

        return response()->json($data);
    }

    public function pengaturan()
    {
        $data = [
            'page_title' => 'Pengaturan Web',
            'breadcumb' => 'Pengaturan Situs Web',
            'pengaturan' => \App\Helpers\BerandaUI::web(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.pengaturan.form-pengaturan-situs', $data);
    }

    public function banner()
    {
        $data = [
            'page_title' => 'Banner Carousel',
            'breadcumb' => 'Manajemen Banner',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.pengaturan.banner-web', $data);
    }

    public function faq()
    {
        $data = [
            'page_title' => 'FAQ',
            'breadcumb' => 'Frequently Asked Questions',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.pengaturan.faq-web', $data);
    }

    public function logs()
    {
        $data = [
            'page_title' => 'Logs',
            'breadcumb' => 'Logs Aktivitas Pengguna',
            'logs' => DB::table('logs')->select('logs.*', 'users.name')->leftJoin('users', 'logs.user_id', '=', 'users.id')->orderBy('created_at', 'DESC')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.pengaturan.logs-web', $data);
    }

    public function profilPengguna()
    {
        $data = [
            'page_title' => 'Profil Pengguna',
            'bc1' => 'Profil Pengguna',
            'bc2' => Auth::user()->name,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.profil.profil-pengguna', $data);
    }
}
