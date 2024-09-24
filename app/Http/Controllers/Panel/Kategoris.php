<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\KategoriRequest;
use App\Models\KategoriProduk;
use App\Models\LimitTryout;
use App\Models\ProdukTryout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Kategoris extends Controller
{
    public function index($produk = null)
    {
        $data = [
            'page_title' => 'Kategori Produk',
            'bc1' => 'Manajemen Produk',
            'bc2' => 'Produk ' . ucfirst($produk),
            'kategori' => KategoriProduk::all(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.kategori.data-kategori-produk', $data);
    }

    public function formKategori($param = null, $id = null)
    {
        if (htmlentities($param) == 'add') {
            $form_title = 'Tambah Kategori';
            $kategori = '';
            $formParam = Crypt::encrypt('add');
        } elseif (htmlentities($param) == 'update') {
            $form_title = 'Edit Kategori';
            $kategori = KategoriProduk::findOrFail(Crypt::decrypt($id));
            $formParam = Crypt::encrypt('update');
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $form_title,
            'bc1' => 'Manajemen Kategori',
            'bc2' => $form_title,
            'kategori' => $kategori,
            'formParam' => $formParam,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Disetujui')->count(),
        ];

        return view('main-panel.kategori.form-kategori-produk', $data);
    }

    public function simpanKategori(KategoriRequest $request): RedirectResponse
    {
        // Autentifikasi user
        $users = Auth::user();
        // Validasi inputan
        $request->validated();

        $updatedId = null;

        if (Crypt::decrypt($request->input('formParameter')) == 'add') {
            $kategori = new KategoriProduk();
            $kategori->judul = ucwords(htmlspecialchars($request->input('judul')));
            $kategori->status = htmlspecialchars($request->input('status'));
            $kategori->aktif = htmlspecialchars($request->input('aktif'));

            // Catatan log
            $logs = $users->name . ' telah menambahkan kategori produk ' . $request->input('judul') . ' waktu tercatat :  ' . now();
            $message = 'Kategori produk berhasil disimpan !';
            $error = 'Kategori produk gagal disimpan !';
        } elseif (Crypt::decrypt($request->input('formParameter')) == 'update') {
            $kategori = KategoriProduk::findOrFail($request->input('kategoriID'));
            $updatedId = $kategori->id;
            $kategori->judul = ucwords(htmlspecialchars($request->input('judul')));
            $kategori->status = htmlspecialchars($request->input('status'));
            $kategori->aktif = htmlspecialchars($request->input('aktif'));

            // Catatan log
            $logs = $users->name . ' telah memperbarui kategori produk dengan ID' . $updatedId . ' waktu tercatat :  ' . now();
            $message = 'Kategori produk berhasil diperbarui !';
            $error = 'Kategori produk gagal diperbarui !';
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }

        $save = $kategori->save();
        if (!$save) {
            return redirect()->back()->with('error', $error)->withInput();
        }

        // Delete Necessarily cache
        Cache::tags(['product_categories_main_web'])->flush();
        if ($updatedId) {
            Cache::tags(['product_category_main_web:' . $updatedId])->flush();
        }

        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('kategori.index', ['produk' => 'tryout'])->with('message', $message);
    }

    public function ubahAktifKategori(Request $request): RedirectResponse
    {
        $kategori = KategoriProduk::findOrFail(Crypt::decrypt($request->id));
        if (!$kategori) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }

        $users = Auth::user();

        $message = '';
        $logs = '';
        if ($kategori->aktif == 'Y') {
            $kategori->aktif = 'T';
            $logs = $users->name . ' telah menonaktifkan kategori dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            $message = 'Kategori produk berhasil dinonaktifkan !';
        } else {
            $kategori->aktif = 'Y';
            $logs = $users->name . ' telah mengaktifkan kategori dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            $message = 'Kategori produk berhasil diaktifkan !';
        }

        $save = $kategori->save();
        if (!$save) {
            return redirect()->back()->with('error', 'Kategori gagal diubah !');
        }

        // Delete Necessarily cache
        Cache::tags(['product_categories_main_web'])->flush();
        Cache::tags(['product_category_main_web:' . $kategori->id])->flush();

        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->back()->with('message', $message);
    }

    public function hapusKategori(Request $request): RedirectResponse
    {
        $kategori = KategoriProduk::findOrFail(Crypt::decrypt($request->id));
        if (!$kategori) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }
        $produkTryout = ProdukTryout::where('kategori_produk_id', $kategori->id)->first();
        if ($produkTryout) {
            return redirect()->back()->with('error', 'Kategori sudah dipakai tryout, tidak dapat dihapus !');
        }

        $delete = $kategori->delete();
        if (!$delete) {
            return redirect()->back()->with('error', 'Kategori gagal dihapus !');
        }

        $users = Auth::user();

        // Delete Necessarily cache
        Cache::tags(['product_categories_main_web'])->flush();
        Cache::tags(['product_category_main_web:' . $kategori->id])->flush();

        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus kategori dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->back()->with('message', 'Kategori berhasil dihapus !');
    }
}
