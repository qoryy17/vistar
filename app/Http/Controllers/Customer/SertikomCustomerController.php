<?php

namespace App\Http\Controllers\Customer;

use PDF;
use App\Models\Customer;
use App\Helpers\RecordLogs;
use Illuminate\Http\Request;
use App\Helpers\QueryCollect;
use App\Models\KategoriProduk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Sertikom\PesertaSertikomModel;
use App\Models\Sertikom\TahapanSertikomModel;
use App\Models\Sertikom\SertifikatSertikomModel;
use App\Models\Sertikom\OrderPelatihanSeminarModel;
use App\Models\Sertikom\ProdukPelatihanSeminarModel;
use App\Http\Requests\Customer\AssignmentTrainingRequest;

class SertikomCustomerController extends Controller
{
    public function getSertikom(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $viewPage = 'customer-panel.sertikom.daftar-pelatihan';
        } elseif ($request->category == 'seminar') {
            $viewPage = 'customer-panel.sertikom.daftar-seminar';
        } elseif ($request->category == 'workshop') {
            $viewPage = 'customer-panel.sertikom.daftar-workshop';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak valid !');
        }

        $data = [
            'page_title' => 'Daftar ' . ucfirst($request->category),
            'breadcumb' => ucfirst($request->category) . ' Tersedia',
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'sertikom' => QueryCollect::getOrderSertikom(['customer' => Auth::user()->customer_id, 'category' => ucfirst($request->category)]),
        ];

        return view($viewPage, $data);
    }

    public function getDetailSertikom(Request $request)
    {
        if ($request->category == 'pelatihan') {
            $viewPage = 'customer-panel.sertikom.detail-pelatihan';
        } elseif ($request->category == 'seminar') {
            $viewPage = 'customer-panel.sertikom.detail-seminar';
        } elseif ($request->category == 'workshop') {
            $viewPage = 'customer-panel.sertikom.detail-workshop';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak valid !');
        }
        $searchOrderSertikom = OrderPelatihanSeminarModel::findOrFail(Crypt::decrypt($request->id));
        $searchSertikom = QueryCollect::getDetailSertikom(['category' => ucfirst($request->category), 'id' => $searchOrderSertikom->produk_pelatihan_seminar_id]);

        // Checking if customer available to become participant
        $searchParticipant = PesertaSertikomModel::where('order_pelatihan_seminar_id', $searchOrderSertikom->id)->first();
        if ($searchParticipant) {
            // Get step on sertikom
            $getStep = TahapanSertikomModel::where('kode', $searchParticipant->tahapan_sertikom_kode)->first();
        } else {
            $searchParticipant = null;
            $getStep = null;
        }

        if (!$searchSertikom) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan, silahkan hubungi kami !');
        }
        $data = [
            'page_title' => 'Detail ' . ucfirst($request->category),
            'breadcumb' => $searchSertikom->produk,
            'customer' => Customer::findOrFail(Auth::user()->customer_id),
            'order' => $searchOrderSertikom,
            'sertikom' => $searchSertikom,
            'participant' => $searchParticipant,
            'currentStep' => $getStep
        ];

        return view($viewPage, $data);
    }

    public function uploadAssignment(AssignmentTrainingRequest $request)
    {
        $request->validated();
        $save = null;

        // get your participant
        $participant = PesertaSertikomModel::findOrFail(Crypt::decrypt($request->input('participantID')));
        $formData = [
            'link_pretest' => htmlspecialchars($request->input('preTest')),
            'link_posttest' => htmlspecialchars($request->input('postTest')),
        ];

        $save = $participant->update($formData);

        if (!$save) {
            return redirect()->route('customer.detail-sertikom', ['category' => 'pelatihan', 'id' => Crypt::encrypt($participant->order_pelatihan_seminar_id)])->with('error', 'Pres Test dan Post Test gagal di simpan !');
        }
        $users = Auth::user();
        // Simpan logs aktivitas pengguna
        $logs = $users->name . ' telah mengupload tugas, param : ' . $formData . ' waktu tercatat :  ' . now();
        RecordLogs::saveRecordLogs($request->ip(), $request->userAgent(), $logs);
        return redirect()->route('customer.detail-sertikom', ['category' => 'pelatihan', 'id' => Crypt::encrypt($participant->order_pelatihan_seminar_id)])->with('message', 'Pres Test dan Post Test berhasil di simpan !');
    }

    public function generateCertificate(Request $request)
    {
        $participantID = Crypt::decrypt($request->id);

        if ($request->category == 'pelatihan' && $request->param == 'pelatihan') {
            $certificateTitle = 'Sertifikat Pelatihan';
            $certificateNote = 'Telah sukses dan berhasil menyelesaikan seluruh rangkaian pelatihan';
        } elseif ($request->category == 'pelatihan' && $request->param == 'kehadiran') {
            $certificateTitle = 'Sertifikat Kehadiran';
            $certificateNote = 'Telah mengikuti seluruh rangkaian pelatihan';
        } elseif ($request->category == 'seminar' && $request->param == 'null') {
            $certificateTitle = 'Sertifikat Kehadiran';
            $certificateNote = 'Telah mengikuti seluruh rangkaian kegiatan seminar';
        } elseif ($request->category == 'workshop' && $request->param == 'pelatihan') {
            $certificateTitle = 'Sertifikat Kehadiran';
            $certificateNote = 'Telah sukses dan berhasil menyelesaikan seluruh rangkaian pelatihan';
        } elseif ($request->category == 'workshop' && $request->param == 'kehadiran') {
            $certificateTitle = 'Sertifikat Kehadiran';
            $certificateNote = 'Telah mengikuti seluruh rangkaian pelatihan';
        } else {
            return redirect()->back()->with('error', 'Kategori tidak valid !');
        }

        $participant = PesertaSertikomModel::find($participantID);
        if (!$participant) {
            return redirect()->route('customer.detail-sertikom', ['category' => $request->category, 'id' => $request->id])->with('error', 'Tidak terdaftar sebagai peserta, gagal mencetak sertifikat !');
        }

        $searchOrder = OrderPelatihanSeminarModel::findOrFail($participant->order_pelatihan_seminar_id);
        $productSertikom = ProdukPelatihanSeminarModel::findOrFail($searchOrder->produk_pelatihan_seminar_id);
        $category = KategoriProduk::findOrFail($productSertikom->kategori_produk_id);

        $urlCertificate = url('/certificate/verification') . '?kode_peserta=' . $participant->kode_peserta;

        // Generate QR code
        $qrCode = base64_encode(QrCode::format('png')->size(60)->generate($urlCertificate));

        // Generate CertificateNumber
        $resultCertificate = $this->generateNumberCertificate([
            'category' => $category->judul,
            'productID' => $productSertikom->id,
            'participantID' => $participant->id
        ]);

        $data = [
            'participant' => $participant,
            'order' => $searchOrder,
            'product' => $productSertikom,
            'title' => $certificateTitle,
            'note' => $certificateNote,
            'qrCode' => $qrCode,
            'certificate' => $resultCertificate,
            'urlVerification' => $urlCertificate
        ];

        $pdf = PDF::loadView('customer-panel.sertikom.template-sertifikat', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($certificateTitle . ' ' . $participant->nama . '-' . $participant->kode_peserta . '.pdf');
    }

    public static function generateNumberCertificate($data)
    {
        $noIndex = 0;
        $save = null;
        $certificateNumber = null;
        // Classification Category;
        if ($data['category'] == 'Pelatihan') {
            $formatNumber = '/TRAINING/VISTAR/' . date('m') . '/' . date('Y');
        } elseif ($data['category'] == 'Seminar') {
            $formatNumber = '/SEMINAR/VISTAR/' . date('m') . '/' . date('Y');
        } elseif ($data['category'] == 'Workshop') {
            $formatNumber = '/WORKSHOP/VISTAR/' . date('m') . '/' . date('Y');
        } else {
            return redirect()->back()->with('error', 'Gagal mencetak sertifikat');
        }

        $checkIndex = SertifikatSertikomModel::count();
        if ($checkIndex == 0) {
            $noIndex = 1;
            $certificateNumber = $noIndex . $formatNumber;
        } else {
            $checkIndex = SertifikatSertikomModel::orderBy('nomor_indeks', 'desc')->limit(1)->first();
            $noIndex = $checkIndex->nomor_indeks + 1;
            $certificateNumber = $noIndex . $formatNumber;
        }

        $insertData = [
            'nomor_indeks' => $noIndex,
            'nomor_sertifikat' => $certificateNumber,
            'produk_pelatihan_seminar_id' => $data['productID'],
            'peserta_sertikom_id' => $data['participantID']
        ];

        $checkNumber = SertifikatSertikomModel::where('peserta_sertikom_id', $data['participantID'])->first();
        if (!$checkNumber) {
            $save = SertifikatSertikomModel::create($insertData);
            if (!$save) {
                return redirect()->back()->with('error', 'Gagal mencetak sertifikat');
            }
        } else {
            return $certificateNumber = $checkNumber->nomor_sertifikat;;
        }

        return $certificateNumber;
    }
}
