<?php

namespace App\Http\Controllers\Panel;

use App\Models\Testimoni;
use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class Testimonis extends Controller
{

    public function index()
    {
        $testimoni = DB::table('testimoni')->select(
            'testimoni.*',
            'customer.nama_lengkap',
            'produk_tryout.nama_tryout'
        )->leftJoin('customer', 'testimoni.customer_id', '=', 'customer.id')
            ->leftJoin('produk_tryout', 'testimoni.produk_tryout_id', '=', 'produk_tryout.id')
            ->orderBy('updated_at', 'DESC')->get();

        $data = [
            'form_title' => 'Data Testimoni',
            'page_title' => 'Testimoni',
            'breadcumb' => 'Manajemen Testimoni',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'testimoni' => $testimoni
        ];
        return view('main-panel.testimoni.data-testimoni', $data);
    }

    public function publishTestimoni(Request $request)
    {
        // Cek testimoni 
        $testimoni = Testimoni::findOrFail(Crypt::decrypt($request->id));
        $testimoni->publish = Crypt::decrypt($request->publish);

        if (Crypt::decrypt($request->publish) == 'Y') {
            $logs = Auth::user()->name . ' telah mengaktifkan publish testimoni dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            $messagePublish = 'Testimoni berhasil dipublish !';
            $errorPublish = 'Testimoni gagal dipublish !';
        } else {
            $messagePublish = 'Testimoni berhasil diunpublish !';
            $errorPublish = 'Testimoni gagal diunpublish !';
            $logs = Auth::user()->name . ' telah menonaktifkan publish testimoni dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        }

        if ($testimoni->save()) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('testimoni.main')->with('message', $messagePublish);
        } else {
            return Redirect::route('testimoni.main')->with('error', $errorPublish);
        }
    }

    public function hapusTestimoni(Request $request)
    {
        $testimoni = Testimoni::findOrFail(Crypt::decrypt($request->id));
        if ($testimoni->delete()) {
            // Simpan logs aktivitas pengguna
            $logs = Auth::user()->name . ' telah menghapus testimoni perserta dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('testimoni.main')->with('message', 'Testimoni berhasil dihapus !');
        } else {
            return Redirect::route('testimoni.main')->with('error', 'Testimoni gagal dihapus !');
        }
    }
}
