<?php

namespace App\Http\Controllers\Sertikom;

use Carbon\Carbon;
use App\Models\Customer;
use App\Helpers\Notifikasi;
use App\Helpers\RecordLogs;
use App\Models\LimitTryout;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\QueryCollect;
use App\Models\KategoriProduk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\Sertikom\InstrukturModel;
use App\Models\Sertikom\TopikKeahlianModel;
use App\Models\Sertikom\PesertaSertikomModel;
use App\Models\Sertikom\TahapanSertikomModel;
use App\Models\Sertikom\OrderPelatihanSeminarModel;
use App\Models\Sertikom\ProdukPelatihanSeminarModel;
use App\Http\Requests\Panel\SertikomInstrukturRequest;
use App\Http\Requests\Panel\SertikomTopikKeahlianRequest;
use App\Http\Requests\Panel\SertikomPelatihanSeminarRequest;

class SertikomController extends Controller
{
    public function sertikomProduct(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $titleForm = ucfirst($request->category);
            $viewPage = 'main-panel.sertikom.training.data-produk-training';
        } elseif ($request->category == 'seminar') {
            $titleForm = ucfirst($request->category);
            $viewPage = 'main-panel.sertikom.seminar.data-produk-seminar';
        } elseif ($request->category == 'workshop') {
            $titleForm = ucfirst($request->category);
            $viewPage = 'main-panel.sertikom.workshop.data-produk-workshop';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }

        $data = [
            'page_title' => 'Produk ' . $titleForm,
            'bc1' => 'Dashboard',
            'bc2' => 'Produk ' . $titleForm,
            'sertikom' => QueryCollect::sertikomProduct($titleForm),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view($viewPage, $data);
    }

    public function formSertikom(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $titleForm = ucfirst($request->category);
            $viewPage = 'main-panel.sertikom.training.form-produk-training';
        } elseif ($request->category == 'seminar') {
            $titleForm = ucfirst($request->category);
            $viewPage = 'main-panel.sertikom.seminar.form-produk-seminar';
        } elseif ($request->category == 'workshop') {
            $titleForm = ucfirst($request->category);
            $viewPage = 'main-panel.sertikom.workshop.form-produk-workshop';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }
        if ($request->param == 'add') {
            $form = 'Tambah Produk ' . $titleForm;
            $paramOutgoing = 'save';
            $searchProductSertikom = null;
        } elseif ($request->param == 'edit') {
            $form = 'Edit Produk ' . $titleForm;
            $paramOutgoing = 'update';
            $searchProductSertikom = ProdukPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->id));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }
        $instructor = InstrukturModel::where('publish', 'Y')->orderBy('created_at', 'asc')->get();
        $categoryProduct = KategoriProduk::where('judul', ucfirst($request->category))->orderBy('created_at', 'asc')->get();
        $expertise = TopikKeahlianModel::where('publish', 'Y')->orderBy('updated_at', 'desc')->get();

        $data = [
            'page_title' => 'Produk ' . $titleForm,
            'bc1' => 'Produk ' . $titleForm,
            'bc2' => $form,
            'form_title' => $form,
            'sertikom' => $searchProductSertikom,
            'param' => Crypt::encrypt($paramOutgoing),
            'instructor' => $instructor,
            'categoryProduct' => $categoryProduct,
            'expertise' => $expertise,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view($viewPage, $data);
    }

    public function saveSertikomTraining(SertikomPelatihanSeminarRequest $request)
    {
        $request->validate([
            'NamaPelatihan' => ['required', 'string', 'max:300']
        ], [
            'NamaPelatihan.required' => 'Nama pelatihan wajib di isi !',
            'NamaPelatihan.string' => 'Nama pelatihan harus berupa string !',
            'NamaPelatihan.max' => 'Nama pelatihan maksimal 300 karakter !',
        ]);
        $request->validated();
        $formData = [
            'kode' => Str::random(10),
            'produk' => htmlspecialchars($request->input('NamaPelatihan')),
            'harga' => htmlspecialchars($request->input('Harga')),
            'deskripsi' => nl2br(htmlspecialchars($request->input('Deskripsi'))),
            'instruktur_id' => htmlspecialchars($request->input('Instruktur')),
            'kategori_produk_id' => htmlspecialchars($request->input('Kategori')),
            'topik_keahlian_id' => htmlspecialchars($request->input('TopikKeahlian')),
            'tanggal_mulai' => Carbon::createFromFormat('d-m-Y', htmlspecialchars($request->input('TanggalMulai')))->format('Y-m-d'),
            'tanggal_selesai' => Carbon::createFromFormat('d-m-Y', htmlspecialchars($request->input('TanggalSelesai')))->format('Y-m-d'),
            'jam_mulai' => htmlspecialchars($request->input('JamMulai')),
            'jam_selesai' => htmlspecialchars($request->input('JamSelesai')),
        ];

        $paramIncoming = Crypt::decrypt($request->input('param'));
        $save = null;

        $uploadedDir = 'images/training/';

        if ($paramIncoming == 'save') {
            // Upload thumbnail produk pelatihan
            if (!$request->file('thumbnail')) {
                return redirect()->back()->with('error', 'Thumbnail belum di unggah ulang !')->withInput();
            }
            $fileThumbnail = $request->file('thumbnail');

            $uploadedFileName = time() . '-' . $fileThumbnail->hashName();
            $uploadedPath = $uploadedDir . $uploadedFileName;

            $fileUpload = $fileThumbnail->storeAs($uploadedDir, $uploadedFileName, 'public');
            if (!$fileUpload) {
                return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
            }

            $formData['thumbnail'] = $uploadedPath;
            $formData['publish'] = htmlspecialchars($request->input('Publish'));
            $formData['link_zoom'] = htmlspecialchars($request->input('LinkZoom'));
            $formData['link_wa'] = htmlspecialchars($request->input('LinkWA'));
            $formData['link_rekaman'] = htmlspecialchars($request->input('LinkRekaman'));
            $formData['status'] = htmlspecialchars($request->input('Status'));

            $save = ProdukPelatihanSeminarModel::create($formData);
            $success = 'Produk pelatihan berhasil disimpan !';
            $error = 'Produk pelatihan gagal disimpan !';
            $log = ' telah menambahkan produk pelatihan ';
        } elseif ($paramIncoming == 'update') {
            $training = ProdukPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->input('Id')));
            if ($request->hasFile('thumbnail')) {
                // Upload thumbnail produk pelatihan
                $fileThumbnail = $request->file('thumbnail');

                $uploadedFileName = time() . '-' . $fileThumbnail->hashName();
                $uploadedPath = $uploadedDir . $uploadedFileName;

                $fileUpload = $fileThumbnail->storeAs($uploadedDir, $uploadedFileName, 'public');
                if (!$fileUpload) {
                    return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
                }

                // Hapus thumbnail yang lama
                if ($training->thumbnail && Storage::disk('public')->exists($training->thumbnail)) {
                    Storage::disk('public')->delete($training->thumbnail);
                }
                $formData['thumbnail'] = $uploadedPath;
            }

            $formData['publish'] = htmlspecialchars($request->input('Publish'));
            $formData['link_zoom'] = htmlspecialchars($request->input('LinkZoom'));
            $formData['link_wa'] = htmlspecialchars($request->input('LinkWA'));
            $formData['link_rekaman'] = htmlspecialchars($request->input('LinkRekaman'));
            $formData['status'] = htmlspecialchars($request->input('Status'));

            $save = $training->update($formData);
            $success = 'Produk pelatihan berhasil diperbarui !';
            $error = 'Produk pelatihan gagal diperbarui !';
            $log = ' telah memperbarui produk pelatihan dengan ID ' . Crypt::decrypt($request->input('Id'));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !')->withInput();
        }

        if (!$save) {
            return redirect()->back()->with('error', $error)->withInput();
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . $log . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('sertikom.product', ['category' => 'pelatihan'])->with('message', $success);
    }

    public function deleteSertikomTraining(Request $request)
    {
        $orderTraining = OrderPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->id));
        if ($orderTraining) {
            return redirect()->route('sertikom.product', ['category' => 'pelatihan'])->with('error', 'Produk pelatihan sudah di order tidak dapat dihapus !');
        }

        $training = ProdukPelatihanSeminarModel::findOrfail(Crypt::decrypt($request->id));
        if (!$training) {
            return redirect()->route('sertikom.product', ['category' => 'pelatihan'])->with('error', 'Produk pelatihan tidak ditemukan !');
        }
        $delete = $training->delete();
        if (!$delete) {
            return redirect()->route('sertikom.product', ['category' => 'pelatihan'])->with('error', 'Produk pelatihan gagal dihapus !');
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus produk pelatihan dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('sertikom.product', ['category' => 'pelatihan'])->with('message', 'Produk pelatihan berhasil dihapus !');
    }

    public function saveSertikomSeminar(SertikomPelatihanSeminarRequest $request)
    {
        $request->validate([
            'NamaSeminar' => ['required', 'string', 'max:300']
        ], [
            'NamaSeminar.required' => 'Nama seminar wajib di isi !',
            'NamaSeminar.string' => 'Nama seminar harus berupa string !',
            'NamaSeminar.max' => 'Nama seminar maksimal 300 karakter !',
        ]);
        $request->validated();
        $formData = [
            'kode' => Str::random(10),
            'produk' => htmlspecialchars($request->input('NamaSeminar')),
            'harga' => htmlspecialchars($request->input('Harga')),
            'deskripsi' => nl2br(htmlspecialchars($request->input('Deskripsi'))),
            'kategori_produk_id' => htmlspecialchars($request->input('Kategori')),
            'topik_keahlian_id' => htmlspecialchars($request->input('TopikKeahlian')),
            'tanggal_mulai' => Carbon::createFromFormat('d-m-Y', htmlspecialchars($request->input('TanggalMulai')))->format('Y-m-d'),
            'tanggal_selesai' => Carbon::createFromFormat('d-m-Y', htmlspecialchars($request->input('TanggalSelesai')))->format('Y-m-d'),
            'jam_mulai' => htmlspecialchars($request->input('JamMulai')),
            'jam_selesai' => htmlspecialchars($request->input('JamSelesai')),
        ];

        $paramIncoming = Crypt::decrypt($request->input('param'));
        $save = null;

        $uploadedDir = 'images/seminar/';

        if ($paramIncoming == 'save') {
            // Upload thumbnail produk seminar
            if (!$request->file('thumbnail')) {
                return redirect()->back()->with('error', 'Thumbnail belum di unggah ulang !')->withInput();
            }
            $fileThumbnail = $request->file('thumbnail');

            $uploadedFileName = time() . '-' . $fileThumbnail->hashName();
            $uploadedPath = $uploadedDir . $uploadedFileName;

            $fileUpload = $fileThumbnail->storeAs($uploadedDir, $uploadedFileName, 'public');
            if (!$fileUpload) {
                return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
            }

            $formData['thumbnail'] = $uploadedPath;
            $formData['publish'] = htmlspecialchars($request->input('Publish'));
            $formData['link_zoom'] = htmlspecialchars($request->input('LinkZoom'));
            $formData['link_wa'] = htmlspecialchars($request->input('LinkWA'));
            $formData['link_rekaman'] = htmlspecialchars($request->input('LinkRekaman'));
            $formData['status'] = htmlspecialchars($request->input('Status'));

            $save = ProdukPelatihanSeminarModel::create($formData);
            $success = 'Produk seminar berhasil disimpan !';
            $error = 'Produk seminar gagal disimpan !';
            $log = ' telah menambahkan produk seminar ';
        } elseif ($paramIncoming == 'update') {
            $seminar = ProdukPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->input('Id')));
            if ($request->hasFile('thumbnail')) {
                // Upload thumbnail produk seminar
                $fileThumbnail = $request->file('thumbnail');

                $uploadedFileName = time() . '-' . $fileThumbnail->hashName();
                $uploadedPath = $uploadedDir . $uploadedFileName;

                $fileUpload = $fileThumbnail->storeAs($uploadedDir, $uploadedFileName, 'public');
                if (!$fileUpload) {
                    return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
                }

                // Hapus thumbnail yang lama
                if ($seminar->thumbnail && Storage::disk('public')->exists($seminar->thumbnail)) {
                    Storage::disk('public')->delete($seminar->thumbnail);
                }
                $formData['thumbnail'] = $uploadedPath;
            }

            $formData['publish'] = htmlspecialchars($request->input('Publish'));
            $formData['link_zoom'] = htmlspecialchars($request->input('LinkZoom'));
            $formData['link_wa'] = htmlspecialchars($request->input('LinkWA'));
            $formData['link_rekaman'] = htmlspecialchars($request->input('LinkRekaman'));
            $formData['status'] = htmlspecialchars($request->input('Status'));

            $save = $seminar->update($formData);
            $success = 'Produk seminar berhasil diperbarui !';
            $error = 'Produk seminar gagal diperbarui !';
            $log = ' telah memperbarui produk seminar dengan ID ' . Crypt::decrypt($request->input('Id'));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !')->withInput();
        }

        if (!$save) {
            return redirect()->back()->with('error', $error)->withInput();
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . $log . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('sertikom.product', ['category' => 'seminar'])->with('message', $success);
    }

    public function deleteSertikomSeminar(Request $request)
    {
        $orderSeminar = OrderPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->id));
        if ($orderSeminar) {
            return redirect()->route('sertikom.product', ['category' => 'seminar'])->with('error', 'Produk seminar sudah di order tidak dapat dihapus !');
        }

        $seminar = ProdukPelatihanSeminarModel::findOrfail(Crypt::decrypt($request->id));
        if (!$seminar) {
            return redirect()->route('sertikom.product', ['category' => 'seminar'])->with('error', 'Produk seminar tidak ditemukan !');
        }
        $delete = $seminar->delete();
        if (!$delete) {
            return redirect()->route('sertikom.product', ['category' => 'seminar'])->with('error', 'Produk seminar gagal dihapus !');
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus produk seminar dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('sertikom.product', ['category' => 'seminar'])->with('message', 'Produk seminar berhasil dihapus !');
    }

    public function saveSertikomWorkshop(SertikomPelatihanSeminarRequest $request)
    {
        $request->validate([
            'NamaWorkshop' => ['required', 'string', 'max:300']
        ], [
            'NamaWorkshop.required' => 'Nama workshop wajib di isi !',
            'NamaWorkshop.string' => 'Nama workshop harus berupa string !',
            'NamaWorkshop.max' => 'Nama workshop maksimal 300 karakter !',
        ]);
        $request->validated();
        $formData = [
            'kode' => Str::random(10),
            'produk' => htmlspecialchars($request->input('NamaWorkshop')),
            'harga' => htmlspecialchars($request->input('Harga')),
            'deskripsi' => nl2br(htmlspecialchars($request->input('Deskripsi'))),
            'instruktur_id' => htmlspecialchars($request->input('Instruktur')),
            'kategori_produk_id' => htmlspecialchars($request->input('Kategori')),
            'topik_keahlian_id' => htmlspecialchars($request->input('TopikKeahlian')),
            'tanggal_mulai' => Carbon::createFromFormat('d-m-Y', htmlspecialchars($request->input('TanggalMulai')))->format('Y-m-d'),
            'tanggal_selesai' => Carbon::createFromFormat('d-m-Y', htmlspecialchars($request->input('TanggalSelesai')))->format('Y-m-d'),
            'jam_mulai' => htmlspecialchars($request->input('JamMulai')),
            'jam_selesai' => htmlspecialchars($request->input('JamSelesai')),
        ];

        $paramIncoming = Crypt::decrypt($request->input('param'));
        $save = null;

        $uploadedDir = 'images/workshop/';

        if ($paramIncoming == 'save') {
            // Upload thumbnail produk seminar
            if (!$request->file('thumbnail')) {
                return redirect()->back()->with('error', 'Thumbnail belum di unggah ulang !')->withInput();
            }
            $fileThumbnail = $request->file('thumbnail');

            $uploadedFileName = time() . '-' . $fileThumbnail->hashName();
            $uploadedPath = $uploadedDir . $uploadedFileName;

            $fileUpload = $fileThumbnail->storeAs($uploadedDir, $uploadedFileName, 'public');
            if (!$fileUpload) {
                return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
            }

            $formData['thumbnail'] = $uploadedPath;
            $formData['publish'] = htmlspecialchars($request->input('Publish'));
            $formData['link_zoom'] = htmlspecialchars($request->input('LinkZoom'));
            $formData['link_wa'] = htmlspecialchars($request->input('LinkWA'));
            $formData['link_rekaman'] = htmlspecialchars($request->input('LinkRekaman'));
            $formData['status'] = htmlspecialchars($request->input('Status'));

            $save = ProdukPelatihanSeminarModel::create($formData);
            $success = 'Produk workshop berhasil disimpan !';
            $error = 'Produk workshop gagal disimpan !';
            $log = ' telah menambahkan produk workshop ';
        } elseif ($paramIncoming == 'update') {
            $workshop = ProdukPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->input('Id')));
            if ($request->hasFile('thumbnail')) {
                // Upload thumbnail produk seminar
                $fileThumbnail = $request->file('thumbnail');

                $uploadedFileName = time() . '-' . $fileThumbnail->hashName();
                $uploadedPath = $uploadedDir . $uploadedFileName;

                $fileUpload = $fileThumbnail->storeAs($uploadedDir, $uploadedFileName, 'public');
                if (!$fileUpload) {
                    return back()->with('error', 'Unggah thumbnail gagal !')->withInput();
                }

                // Hapus thumbnail yang lama
                if ($workshop->thumbnail && Storage::disk('public')->exists($workshop->thumbnail)) {
                    Storage::disk('public')->delete($workshop->thumbnail);
                }
                $formData['thumbnail'] = $uploadedPath;
            }

            $formData['publish'] = htmlspecialchars($request->input('Publish'));
            $formData['link_zoom'] = htmlspecialchars($request->input('LinkZoom'));
            $formData['link_wa'] = htmlspecialchars($request->input('LinkWA'));
            $formData['link_rekaman'] = htmlspecialchars($request->input('LinkRekaman'));
            $formData['status'] = htmlspecialchars($request->input('Status'));

            $save = $workshop->update($formData);
            $success = 'Produk workshop berhasil diperbarui !';
            $error = 'Produk workshop gagal diperbarui !';
            $log = ' telah memperbarui produk workshop dengan ID ' . Crypt::decrypt($request->input('Id'));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !')->withInput();
        }

        if (!$save) {
            return redirect()->back()->with('error', $error)->withInput();
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . $log . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('sertikom.product', ['category' => 'workshop'])->with('message', $success);
    }

    public function deleteSertikomWorkshop(Request $request)
    {
        $orderWorkshop = OrderPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->id));
        if ($orderWorkshop) {
            return redirect()->route('sertikom.training')->with('error', 'Produk workshop sudah di order tidak dapat dihapus !');
        }

        $workshop = ProdukPelatihanSeminarModel::findOrfail(Crypt::decrypt($request->id));
        if (!$workshop) {
            return redirect()->route('sertikom.product', ['category' => 'workshop'])->with('error', 'Produk workshop tidak ditemukan !');
        }
        $delete = $workshop->delete();
        if (!$delete) {
            return redirect()->route('sertikom.product', ['category' => 'workshop'])->with('error', 'Produk workshop gagal dihapus !');
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus produk workshop dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('sertikom.product', ['category' => 'workshop'])->with('message', 'Produk workshop berhasil dihapus !');
    }

    public function sertikomExpertise()
    {
        $data = [
            'page_title' => 'Topik Keahlian',
            'bc1' => 'Dashboard',
            'bc2' => 'Topik Keahlian',
            'expertise' => TopikKeahlianModel::orderBy('updated_at', 'desc')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.sertikom.topik.data-topik-keahlian', $data);
    }

    public function formSertikomExpertise(Request $request)
    {
        if ($request->param == 'add') {
            $form = 'Tambah Topik Keahlian';
            $paramOutgoing = 'save';
            $searchExpertise = null;
        } elseif ($request->param == 'edit') {
            $form = 'Edit Topik Keahlian';
            $paramOutgoing = 'update';
            $searchExpertise = TopikKeahlianModel::findOrFail(Crypt::decrypt($request->id));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => 'Topik Keahlian',
            'bc1' => 'Topik Keahlian',
            'bc2' => $form,
            'form_title' => $form,
            'expertise' => $searchExpertise,
            'param' => Crypt::encrypt($paramOutgoing),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.sertikom.topik.form-topik-keahlian', $data);
    }

    public function saveSertikomExpertise(SertikomTopikKeahlianRequest $request)
    {
        $request->validated();
        $formData = [
            'topik' => htmlspecialchars($request->input('TopikKeahlian')),
            'deskripsi' => nl2br(htmlspecialchars($request->input('Deskripsi'))),
            'publish' => htmlspecialchars($request->input('Publish'))
        ];

        $paramIncoming = Crypt::decrypt($request->input('param'));
        $save = null;

        if ($paramIncoming == 'save') {
            $save = TopikKeahlianModel::create($formData);
            $success = 'Topik Keahlian berhasil disimpan !';
            $error = 'Topik Keahlian gagal disimpan !';
            $log = ' telah menambahkan topik keahlian ';
        } elseif ($paramIncoming == 'update') {
            $expertise = TopikKeahlianModel::findOrFail(Crypt::decrypt($request->input('Id')));
            $save = $expertise->update($formData);
            $success = 'Topik Keahlian berhasil diperbarui !';
            $error = 'Topik Keahlian gagal diperbarui !';
            $log = ' telah memperbarui topik keahlian dengan ID ' . Crypt::decrypt($request->input('Id'));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !')->withInput();
        }

        if (!$save) {
            return redirect()->back()->with('error', $error)->withInput();
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . $log . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('sertikom.expertise')->with('message', $success);
    }

    public function deleteSertikomExpertise(Request $request)
    {
        $expertise = TopikKeahlianModel::findOrfail(Crypt::decrypt($request->id));
        if (!$expertise) {
            return redirect()->route('sertikom.expertise')->with('error', 'Topik Keahlian tidak ditemukan !');
        }
        $delete = $expertise->delete();
        if (!$delete) {
            return redirect()->route('sertikom.expertise')->with('error', 'Topik Keahlian gagal dihapus !');
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus topik keahlian dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('sertikom.expertise')->with('message', 'Topik Keahlian berhasil dihapus !');
    }

    public function sertikomInstructor()
    {
        $data = [
            'page_title' => 'Instruktur',
            'bc1' => 'Dashboard',
            'bc2' => 'Daftar Instruktur',
            'instructor' => InstrukturModel::orderBy('updated_at', 'desc')->get(),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];
        return view('main-panel.sertikom.instruktur.data-instruktur', $data);
    }

    public function formSertikomInstructor(Request $request)
    {
        if ($request->param == 'add') {
            $form = 'Tambah Instruktur';
            $paramOutgoing = 'save';
            $searchInstructor = null;
        } elseif ($request->param == 'edit') {
            $form = 'Edit Instruktur';
            $paramOutgoing = 'update';
            $searchInstructor = InstrukturModel::findOrFail(Crypt::decrypt($request->id));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !');
        }

        $data = [
            'page_title' => 'Instruktur',
            'bc1' => 'Instruktur',
            'bc2' => $form,
            'form_title' => $form,
            'instructor' => $searchInstructor,
            'param' => Crypt::encrypt($paramOutgoing),
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view('main-panel.sertikom.instruktur.form-instruktur', $data);
    }

    public function saveSertikomInstructor(SertikomInstrukturRequest $request)
    {
        $request->validated();
        $formData = [
            'instruktur' => htmlspecialchars($request->input('Instruktur')),
            'keahlian' => htmlspecialchars($request->input('Keahlian')),
            'deskripsi' => nl2br(htmlspecialchars($request->input('Deskripsi'))),
            'publish' => htmlspecialchars($request->input('Publish'))
        ];

        $paramIncoming = Crypt::decrypt($request->input('param'));
        $save = null;

        if ($paramIncoming == 'save') {
            $save = InstrukturModel::create($formData);
            $success = 'Instruktur berhasil disimpan !';
            $error = 'Instruktur gagal disimpan !';
            $log = ' telah menambahkan instruktur ';
        } elseif ($paramIncoming == 'update') {
            $instructor = InstrukturModel::findOrFail(Crypt::decrypt($request->input('Id')));
            $save = $instructor->update($formData);
            $success = 'Instruktur berhasil diperbarui !';
            $error = 'Instruktur gagal diperbarui !';
            $log = ' telah memperbarui instruktur dengan ID ' . Crypt::decrypt($request->input('Id'));
        } else {
            return redirect()->back()->with('error', 'Parameter tidak valid !')->withInput();
        }

        if (!$save) {
            return redirect()->back()->with('error', $error)->withInput();
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . $log . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);

        return redirect()->route('sertikom.instructor')->with('message', $success);
    }

    public function deleteSertikomInstructor(Request $request)
    {
        $instructor = InstrukturModel::findOrfail(Crypt::decrypt($request->id));
        if (!$instructor) {
            return redirect()->route('sertikom.instructor')->with('error', 'Instruktur tidak ditemukan !');
        }
        $delete = $instructor->delete();
        if (!$delete) {
            return redirect()->route('sertikom.instructor')->with('error', 'Instruktur gagal dihapus !');
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus instruktur dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('sertikom.instructor')->with('message', 'Instruktur berhasil dihapus !');
    }

    public function getDetailSertikom(Request $request)
    {
        if (strtolower($request->category) == 'pelatihan') {
            $stepCode = 'PEL' . Str::random(5);
            $viewPage = 'main-panel.sertikom.training.detail-pelatihan';
        } elseif (strtolower($request->category) == 'seminar') {
            $stepCode = 'SEM' . Str::random(5);
            $viewPage = 'main-panel.sertikom.seminar.detail-seminar';
        } elseif (strtolower($request->category) == 'workshop') {
            $stepCode = 'WORK' . Str::random(5);
            $viewPage = 'main-panel.sertikom.workshop.detail-workshop';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak valid !');
        }

        $searchSertikom = QueryCollect::getDetailSertikom(['category' => ucfirst($request->category), 'id' => Crypt::decrypt($request->id)]);

        if (!$searchSertikom) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan, silahkan hubungi kami !');
        }

        $checkStep = TahapanSertikomModel::where('produk_pelatihan_seminar_id', Crypt::decrypt($request->id))->first();
        if (!$checkStep) {
            $param = Crypt::encrypt('save');
            $stepID = null;
            $stepSertikom = null;
            $participant = null;
            $stepCode = $stepCode;
        } else {
            $param = Crypt::encrypt('update');
            $stepID = $checkStep->id;
            $stepSertikom = $checkStep->tahapan;
            $participant = PesertaSertikomModel::where('tahapan_sertikom_kode', $checkStep->kode)->orderBy('nama', 'asc');
            $stepCode = $checkStep->kode;
        }

        $data = [
            'page_title' => $searchSertikom->produk,
            'bc1' => 'Detil',
            'bc2' => ucfirst($request->category) . ' ' . $searchSertikom->produk,
            'sertikom' => $searchSertikom,
            'param' => $param,
            'stepID' => $stepID,
            'stepCode' => $stepCode,
            'currentStep' => $stepSertikom,
            'participant' => $participant,
            'notifTryoutGratis' => Notifikasi::tryoutGratis(),
            'countNotitTryoutGratis' => LimitTryout::where('status_validasi', 'Menunggu')->count(),
        ];

        return view($viewPage, $data);
    }

    public function createStepSertikom(Request $request)
    {
        $users = Auth::user();
        $request->validate(['Tahapan' => ['required']], ['Tahapan.required' => 'Tahapan wajib dipilih !']);

        if (Crypt::decrypt($request->input('Category')) == 'Pelatihan') {
            $redirectRoute = 'sertikom.training-detail';
        } elseif (Crypt::decrypt($request->input('Category')) == 'Seminar') {
            $redirectRoute = 'sertikom.seminar-detail';
        } elseif (Crypt::decrypt($request->input('Category')) == 'Workshop') {
            $redirectRoute = 'sertikom.workshop-detail';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }

        $formData = [
            'kode' => htmlspecialchars($request->input('Kode')),
            'produk_pelatihan_seminar_id' => htmlspecialchars(Crypt::decrypt($request->input('IDSertikom'))),
            'tahapan' => htmlspecialchars($request->input('Tahapan')),
        ];

        // Update product sertikom 
        $product = ProdukPelatihanSeminarModel::find(htmlspecialchars(Crypt::decrypt($request->input('IDSertikom'))));
        if ($product) {
            $product->update(['status' => 'Sold Out']);
        }

        if (Crypt::decrypt($request->input('Param')) == 'save') {

            $saveStep = TahapanSertikomModel::create($formData);

            if (!$saveStep) {
                return redirect()->back()->with('error', 'Gagal membuat tahapan !');
            }

            // get all customer for participat training/seminar/workshop from order
            $getOrder = OrderPelatihanSeminarModel::where('produk_pelatihan_seminar_id', htmlspecialchars(Crypt::decrypt($request->input('IDSertikom'))))->get();

            $participant = [];
            foreach ($getOrder as $order) {
                $customer = Customer::find($order->customer_id);
                $participant[] = [
                    'tahapan_sertikom_kode' => htmlspecialchars($request->input('Kode')),
                    'kode_peserta' => Str::random(5),
                    'order_pelatihan_seminar_id' => $order->id,
                    'nama' => $order->nama,
                    'kontak' => $customer->kontak
                ];
            }

            $saveParticipant = PesertaSertikomModel::insert($participant);

            if (!$saveParticipant) {
                return redirect()->back()->with('error', 'Gagal membuat peserta ' . Crypt::decrypt($request->input('Category')));
                // Delete step
                $deleteStep = TahapanSertikomModel::findOrFail(htmlspecialchars(Crypt::decrypt($request->input('IDSertikom'))));
                $deleteStep->delete();
            }

            $success = 'Tahapan ' . Crypt::decrypt($request->input('Category')) . ' berhasil disimpan !';
            $logs = $users->name . ' telah membuat tahapan ' . Crypt::decrypt($request->input('Category')) . ' dengan kode : ' . htmlspecialchars($request->input('Kode')) . ' waktu tercatat :  ' . now();
        } else if (Crypt::decrypt($request->input('Param')) == 'update') {
            $stepSertikom = TahapanSertikomModel::findOrFail(Crypt::decrypt($request->input('IDStep')));
            $saveStep = $stepSertikom->update($formData);

            if (!$saveStep) {
                return redirect()->back()->with('error', 'Gagal membuat tahapan !');
            }

            $success = 'Tahapan ' . Crypt::decrypt($request->input('Category')) . ' berhasil diperbarui !';
            $logs = $users->name . ' telah memperbarui tahapan ' . Crypt::decrypt($request->input('Category')) . ' dengan id : ' . Crypt::decrypt($request->input('IDStep')) . ' waktu tercatat :  ' . now();
        } else {
            return redirect()->back()->with('eror', 'Parameter tidak valid !');
        }
        // Simpan logs aktivitas pengguna
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route($redirectRoute, ['id' => $request->input('IDSertikom'), 'category' => Crypt::decrypt($request->input('Category'))])->with('message', $success);
    }

    public function deleteStepSertikom(Request $request)
    {
        // Update product sertikom 
        $product = ProdukPelatihanSeminarModel::find(htmlspecialchars(Crypt::decrypt($request->id)));
        if ($product) {
            $product->update(['status' => 'Tersedia']);
        }

        if ($request->category == 'pelatihan') {
            $redirectRoute = 'sertikom.training-detail';
        } elseif ($request->category == 'seminar') {
            $redirectRoute = 'sertikom.seminar-detail';
        } elseif ($request->category == 'workshop') {
            $redirectRoute = 'sertikom.workshop-detail';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }

        $searchStep = TahapanSertikomModel::where('produk_pelatihan_seminar_id', Crypt::decrypt($request->id))->first();
        if (!$searchStep) {
            return redirect()->back()->with('error', 'Tahapan tidak ditemukan !');
        }
        /* If step has found, delete all participant
            This admin must becareful to delete step on sertikom (training, seminar/workshop)
        */

        $searchParticipant = PesertaSertikomModel::where('tahapan_sertikom_kode', $searchStep->kode)->first();
        if (!$searchParticipant) {
            $deleteStep = $searchStep->delete();
            return redirect()->back()->with('error', 'Peserta tidak ditemukan !');
        }

        $deleteParticipant = $searchParticipant->delete();
        if (!$deleteParticipant) {
            return redirect()->back()->with('error', 'Peserta gagal dihapus !');
        }

        $deleteStep = $searchStep->delete();
        if (!$deleteStep) {
            return redirect()->back()->with('error', 'Tahapan gagal dihapus !');
        }

        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus tahapan dan peserta dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route($redirectRoute, ['id' => $request->id, 'category' => $request->category])->with('message', 'Tahapan dan peserta berhasil dihapus !');
    }

    public function deleteParticipantSertikom(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $redirectRoute = 'sertikom.training-detail';
        } elseif ($request->category == 'seminar') {
            $redirectRoute = 'sertikom.seminar-detail';
        } elseif ($request->category == 'workshop') {
            $redirectRoute = 'sertikom.workshop-detail';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan !');
        }

        $participant = PesertaSertikomModel::findOrfail(Crypt::decrypt($request->id));
        if (!$participant) {
            return redirect()->route($redirectRoute, ['id' => $request->idSertikom, 'category' => $request->category])->with('error', 'Peserta tidak ditemukan !');
        }
        $delete = $participant->delete();
        if (!$delete) {
            return redirect()->route($redirectRoute, ['id' => $request->idSertikom, 'category' => $request->category])->with('error', 'Peserta gagal dihapus !');
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah menghapus perserta dengan ID ' . Crypt::decrypt($request->id) . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route($redirectRoute, ['id' => $request->idSertikom, 'category' => $request->category])->with('message', 'Peserta berhasil dihapus !');
    }
}
