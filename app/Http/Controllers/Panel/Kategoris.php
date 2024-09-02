<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Panel\KategoriRequest;
use App\Models\ProdukTryout;

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
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count()
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
            return Redirect::back()->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => $form_title,
            'bc1' => 'Manajemen Kategori',
            'bc2' => $form_title,
            'kategori' => $kategori,
            'formParam' => $formParam,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Disetujui')->count()
        ];
        return view('main-panel.kategori.form-kategori-produk', $data);
    }

    public function simpanKategori(KategoriRequest $request): RedirectResponse
    {
        // Autentifikasi user
        $users = Auth::user();
        // Validasi inputan
        $request->validated();
        if (Crypt::decrypt($request->input('formParameter')) == 'add') {

            $kategori = new KategoriProduk();
            $kategori->id = rand(1, 999) . rand(1, 99);
            $kategori->judul = ucwords(htmlspecialchars($request->input('judul')));
            $kategori->status = htmlspecialchars($request->input('status'));
            $kategori->aktif = htmlspecialchars($request->input('aktif'));

            // Catatan log
            $logs = $users->name . ' telah menambahkan kategori produk ' . $request->input('judul') . ' waktu tercatat :  ' . now();
            $message = 'Kategori produk berhasil disimpan !';
            $error = 'Kategori produk gagal disimpan !';
        } elseif (Crypt::decrypt($request->input('formParameter')) == 'update') {
            $kategori = KategoriProduk::findOrFail($request->input('kategoriID'));
            $kategori->judul = ucwords(htmlspecialchars($request->input('judul')));
            $kategori->status = htmlspecialchars($request->input('status'));
            $kategori->aktif = htmlspecialchars($request->input('aktif'));

            // Catatan log
            $logs = $users->name . ' telah memperbarui kategori produk dengan ID' . $request->input('kategoriID') . ' waktu tercatat :  ' . now();
            $message = 'Kategori produk berhasil diperbarui !';
            $error = 'Kategori produk gagal diperbarui !';
        } else {
            return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('error', 'Parameter tidak valid !');
        }

        if ($kategori->save()) {
            // Simpan logs aktivitas pengguna
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('message', $message);
        } else {
            return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('error', $error)->withInput();
        }
    }

    public function ubahAktifKategori(Request $request): RedirectResponse
    {
        $kategori = KategoriProduk::findOrFail(Crypt::decrypt($request->id));
        if ($kategori) {
            $users = Auth::user();
            if ($kategori->aktif == 'Y') {
                $kategori->aktif = 'T';
                $kategori->save();
                // Simpan logs aktivitas pengguna
                $logs = $users->name . ' telah menonaktifkan kategori dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('message', 'Kategori produk berhasil dinonaktifkan !');
            } else {
                $kategori->aktif = 'Y';
                $kategori->save();
                // Simpan logs aktivitas pengguna
                $logs = $users->name . ' telah mengaktifkan kategori dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
                RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
                return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('message', 'Kategori produk berhasil diaktifkan !');
            }
        }
        return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('error', 'Kategori gagal diubah !');
    }

    public function hapusKategori(Request $request): RedirectResponse
    {
        $kategori = KategoriProduk::findOrFail(Crypt::decrypt($request->id));
        if ($kategori) {
            $produkTryout = ProdukTryout::where('kategori_produk_id', $kategori->id);
            if ($produkTryout) {
                return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('error', 'Kategori sudah dipakai tryout, tidak dapat dihapus !');
            }
            $users = Auth::user();
            $kategori->delete();
            // Simpan logs aktivitas pengguna
            $logs = $users->name . ' telah menghapus kategori dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
            RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
            return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('message', 'Kategori berhasil dihapus !');
        }
        return Redirect::route('kategori.index', ['produk' => 'tryout'])->with('error', 'Kategori gagal dihapus !');
    }
}
