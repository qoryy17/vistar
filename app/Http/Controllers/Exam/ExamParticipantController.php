<?php

namespace App\Http\Controllers\Exam;

use App\Models\User;
use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use App\Models\OrderTryout;
use Illuminate\Support\Str;
use App\Models\ProdukTryout;
use Illuminate\Http\Request;
use App\Helpers\QueryCollect;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Panel\ParticipantRequest;

class ExamParticipantController extends Controller
{
    public function examProducts()
    {
        $data = [
            'form_title' => 'Partisipan Ujian',
            'page_title' => 'Partisipan Ujian',
            'bc1' => 'Dashboard',
            'bc2' => 'Manajemen Ujian',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'products' => QueryCollect::examProducts()
        ];

        return view('main-panel.ujian.data-partisipasi-ujian', $data);
    }

    public function formExamSpecial(Request $request)
    {
        $param = Crypt::decrypt($request->param);
        $id = Crypt::decrypt($request->id);
        if ($param == 'add') {
            $form_title = 'Tambah Partisipan Ujian';
            $examSpecial = null;
        } elseif ($param == 'update') {
            $form_title = 'Edit Partisipan Ujian';
            $examSpecial = OrderTryout::findOrFail($id);
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }
        $data = [
            'form_title' => $form_title,
            'page_title' => 'Ujian Untuk Customer',
            'bc1' => 'Dashboard',
            'bc2' => 'Manajemen Ujian',
            'examSpecial' => $examSpecial,
            'formParam' => $request->param,
            'products' => ProdukTryout::where('status', 'Tersedia')->get(),
            'customers' => User::where('role', 'Customer')->where('blokir', 'T')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.ujian.form-partisipasi-ujian', $data);
    }
    public function examParticipant(Request $request)
    {
        $productID = Crypt::decrypt($request->id);

        $onOrder = OrderTryout::where('produk_tryout_id', $productID)->where('khusus', '1')->get();
        $product = ProdukTryout::findOrFail($productID);

        $data = [
            'form_title' => 'Partisipan Ujian',
            'page_title' => 'Partisipan Penerima Ujian ' . $product->nama_tryout,
            'bc1' => 'Dashboard',
            'bc2' => 'Manajemen Ujian',
            'bc3' => 'Partisipan Ujian',
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
            'participants' => $onOrder
        ];

        return view('main-panel.ujian.data-partisipan-ujian', $data);
    }

    public function examParticipantDetail(Request $request)
    {
        $customerID = Crypt::decrypt($request->id);
        $onOrder = DB::table('order_tryout')->select(
            'order_tryout.id',
            'order_tryout.nama',
            'order_tryout.produk_tryout_id',
            'produk_tryout.nama_tryout',
            'produk_tryout.keterangan'
        )->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->where('order_tryout.customer_id', $customerID)->where('khusus', '1');

        $data = [
            'examTryout' => $onOrder->get(),
            'form_title' => 'Partisipan Ujian',
            'page_title' => 'Detail Partisipan ' . $onOrder->first()->nama,
            'bc1' => 'Dashboard',
            'bc2' => 'Manajemen Ujian',
            'bc3' => 'Partisipan Ujian',
            'backLink' => $onOrder->first()->produk_tryout_id,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.ujian.detail-partisipan-ujian', $data);
    }

    public function saveExamSpecial(ParticipantRequest $request)
    {
        $param = Crypt::decrypt($request->input('formParameter'));

        // Autentifikasi user
        $users = Auth::user();
        // Validasi inputan
        $request->validated();

        $getCustomer = User::findOrFail(htmlspecialchars($request->input('customer')));

        $savedData = [
            'customer_id' => $getCustomer->customer_id,
            'nama' => $getCustomer->name,
            'produk_tryout_id' => htmlspecialchars($request->input('produk')),
            'status_order' => 'paid',
            'khusus' => 1
        ];

        $save = null;
        if ($param == 'add') {

            $checkProduct = OrderTryout::where('produk_tryout_id', $savedData['produk_tryout_id'])->where('customer_id', $savedData['customer_id'])->first();
            if ($checkProduct) {
                return redirect()->back()->with('error', 'Produk ini sudah ditambahkan untuk partispan \n' . $savedData['nama'])->withInput();
            }
            $savedData = array_merge(['id' => Str::uuid()], $savedData);
            $save = OrderTryout::create($savedData);

            // Catatan logs
            $logs = $users->name . ' telah menambahkan ujian partisipan dengan ID Produk ' . $request->input('produk') . ' waktu tercatat :  ' . now();
            $message = 'Ujian Partisipan berhasil disimpan !';
            $error = 'Ujian Partisipan gagal disimpan !';
        } elseif ($param == 'update') {

            $examTryout = OrderTryout::findOrFail($request->input('orderID'));
            $save = $examTryout->update($savedData);

            // Catatan logs
            $logs = $users->name . ' telah memperbarui ujian partisipan dengan ID Produk  ' . $request->input('produk') . ' waktu tercatat :  ' . now();
            $message = 'Ujian Partisipan berhasil diperbarui !';
            $error = 'Ujian Partisipan gagal diperbarui !';
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }

        if (!$save) {
            return redirect()->route('exam-special.form', ['param' => $request->input('formParameter'), 'id' => Crypt::encrypt('exam')])->with('error', $error)->withInput();
        }

        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('exam-special.products')->with('message', $message);
    }

    public function deleteExamSpecial(Request $request)
    {
        $users = Auth::user();
        $id = Crypt::decrypt($request->id);
        $param = Crypt::decrypt($request->param);

        if ($param == 'all') {
            $tryoutOnOrder = OrderTryout::where('customer_id', $id);

            // Catatan logs
            $logs = $users->name . ' telah menghapus partisipan dengan customer id ' . $id . ' waktu tercatat :  ' . now();
            $message = 'Partisipan berhasil dihapus !';
            $error = 'Partisipan gagal dihapus !';
        } elseif ($param == 'half') {
            $tryoutOnOrder = OrderTryout::where('id', $id);

            // Catatan logs
            $logs = $users->name . ' telah menghapus produk tryout pada partisipan dengan customer id ' . $id . ' waktu tercatat :  ' . now();
            $message = 'Produk Tryout pada partisipan berhasil dihapus !';
            $error = 'Produk Tryout pada partisipan gagal dihapus !';
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }

        if (!$tryoutOnOrder->first()) {
            return redirect()->route('exam-special.products')->with('error', 'Data tidak ditemukan !');
        }

        $execDelete = $tryoutOnOrder->delete();

        if (!$execDelete) {
            return redirect()->route('exam-special.products')->with('error', $error);
        }

        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('exam-special.products')->with('message', $message);
    }
}
