@extends('customer-panel.layout.main')
@section('title', $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $page_title }}</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('customer.get-sertikom', ['category' => 'pelatihan']) }}">Pelatihan</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">

                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                <div class="row">
                    <!-- Left side -->
                    <div class="col-lg-4">
                        <div class="card overflow-hidden custom-card ">
                            <div id="lightgallery">
                                <div data-responsive="{{ asset('storage/' . $sertikom->thumbnail) }}"
                                    data-src="{{ asset('storage/' . $sertikom->thumbnail) }}"
                                    data-sub-html="<h4>{{ $sertikom->produk }}</h4><p>{{ $sertikom->topik }}</p>">
                                    <a href="" class="wd-100p">
                                        <img class="img-fluid" src="{{ asset('storage/' . $sertikom->thumbnail) }}"
                                            alt="Thumbnail {{ $sertikom->produk }}"
                                            title="Thumbnail {{ $sertikom->produk }}" loading="lazy" />
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="text-center">Informasi Pembelian</h5>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td>Order ID : <br> {{ $order->id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Faktur ID : <br> {{ $order->faktur_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment ID : <br> {{ $order->id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pembeli : <br> {{ $order->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td>Harga : <br> Rp. {{ Number::Format($sertikom->harga, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Transaksi : <br> {{ $order->created_at }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <a href="{{ route('customer.get-sertikom', ['category' => 'pelatihan']) }}"
                                    class="btn btn-block btn-primary btn-sm">
                                    <i class="fa fa-reply"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End left side -->

                    <!-- Left right -->
                    <div class="col-lg-8">
                        <div class="card custom-card">
                            <div class="card-body">
                                <h3 class="text-primary">
                                    "{{ $sertikom->produk }}"
                                </h3>
                                <p class="m-0 p-0" style="text-align: justify;">
                                    Topik : {{ $sertikom->topik }}
                                    <br>
                                    <span class="mt-5">
                                        Deskripsi : {{ $sertikom->deskripsi }}
                                    </span>
                                </p>
                                <p class="m-0 p-0">
                                    <span>Instuktur : {{ $sertikom->instruktur }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <h5>Jadwal Pelatihan</h5>
                                        <p class="m-0 p-0">
                                            Tanggal :
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $sertikom->tanggal_mulai)->format('d/m/Y') }}
                                            -
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $sertikom->tanggal_selesai)->format('d/m/Y') }}
                                            <br>
                                            Waktu : {{ $sertikom->jam_mulai }} s/d {{ $sertikom->jam_selesai }} Wib
                                        </p>
                                        <small class="text-danger mt-1">
                                            Apabila terdapat perubahan akan dihubungi oleh admin
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <h5>Informasi Link</h5>
                                        <ul class="mb-0 pb-0">
                                            @if ($sertikom->link_wa != null)
                                                <li>
                                                    <a target="_blank" title="Gabung WhatsApp Group"
                                                        href="{{ $sertikom->link_wa }}">
                                                        Gabung WhatsApp Group
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($sertikom->link_zoom != null)
                                                <li>
                                                    <a target="_blank" title="Gabung Zoom Meeting"
                                                        href="{{ $sertikom->link_zoom }}">
                                                        Zoom Meeting
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($sertikom->link_rekaman != null)
                                                <li>
                                                    <a target="_blank" title="Unduh Rekaman"
                                                        href="{{ $sertikom->link_rekaman }}">
                                                        Rekaman Pelatihan
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card">
                            <div class="card-body">
                                @if ($participant == null)
                                    <h5 class="mb-3">
                                        Informasi Pelatihan
                                    </h5>
                                    <div class="alert alert-primary" role="alert" style="text-align: justify;">
                                        Saat ini kamu dalam proses pendaftaran peserta pelatihan
                                        "<strong>{{ $sertikom->produk }}</strong>",
                                        mohon untuk dapat menunggu dan
                                        memantau dashboard ini ya...
                                    </div>
                                @else
                                    @if ($currentStep->tahapan == 'Proses')
                                        <h5 class="mb-3">
                                            Informasi Pelatihan
                                        </h5>
                                        <div class="alert alert-warning" role="alert" style="text-align: justify;">
                                            Saat ini pelatihan sedang berlangsung. Pengumpulan hasil pengerjaan Pre Test dan
                                            Post Test akan dibuka setelah seluruh materi pelatihan telah dituntaskan !
                                        </div>
                                    @elseif ($currentStep->tahapan == 'Tugas')
                                        <h5 class="mb-3">
                                            Informasi Tugas
                                        </h5>
                                        <div class="alert alert-primary" role="alert" style="text-align: justify;">
                                            Horee.. materi pelatihan baru telah selesai di paparkan oleh instruktur. Untuk
                                            menguji pemahaman kamu silahkan mengerjakan Pre Test dan Post Test dari
                                            instruktur ya. Dan jangan lupa untuk mengunggah pada formulir dibawah ini dalam
                                            bentuk link drive. Setelah di kirim kamu akan mendapatkan sertifikat setelah
                                            hasil Pre Test dan Post Test kamu direview oleh instruktur.
                                        </div>
                                        <form action="{{ route('customer.upload-assignment-sertikom') }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <div class="form-group" hidden>
                                                <label for="participantID">
                                                    Participant ID <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Order ID"
                                                    id="participantID" name="participantID"
                                                    value="{{ Crypt::encrypt($participant->id) }}" readonly
                                                    autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label for="preTest">
                                                    Link Pre Test <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control"
                                                    placeholder="Masukan Link Hasil Pengerjaan Pre Test.." id="preTest"
                                                    name="preTest"
                                                    value="{{ old('preTest') ? old('preTest') : $participant->link_pretest }}"
                                                    autocomplete="off">
                                                @error('preTest')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="postTest">
                                                    Link Post Test <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control"
                                                    placeholder="Masukan Link Hasil Pengerjaan Post Test.." id="postTest"
                                                    name="postTest"
                                                    value="{{ old('postTest') ? old('postTest') : $participant->link_posttest }}"
                                                    autocomplete="off">
                                                @error('postTest')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                        </form>
                                    @elseif ($currentStep->tahapan == 'Review')
                                        <h5 class="mb-3">
                                            Informasi Tugas
                                        </h5>
                                        <div class="alert alert-warning" role="alert" style="text-align: justify;">
                                            Saat ini hasil Pre Test dan Post Test kamu sedang ditinjau instruktur ya.
                                            Silahkan tunggu hasil informasi dari kami melalui Grup WhatsApp
                                        </div>
                                        <div class="d-inline-block">
                                            <a target="_blank" class="btn btn-primary btn-sm d-block d-md-inline-block"
                                                href="{{ $participant->link_pretest }}" title="Link Pre Test">
                                                Link Pre Test : Klik Untuk Lihat <i class="fa fa-link"></i>
                                            </a>

                                            <a target="_blank" class="btn btn-primary btn-sm d-block d-md-inline-block"
                                                href="{{ $participant->link_posttest }}" title="Link Post Test">
                                                Link Post Test : Klik Untuk Lihat <i class="fa fa-link"></i>
                                            </a>
                                        </div>
                                    @elseif($currentStep->tahapan == 'Selesai')
                                        <div class="alert alert-success mb-0" role="alert">
                                            <h5 class="alert-heading">Unduh Sertifikat Kamu</h5>
                                            <p>
                                                Selamat kamu telah selesai dan berhasil mengikuti pelatihan
                                                "{{ $sertikom->produk }}".
                                                Untuk selanjutnya kamu dapat mengunduh sertikat dibawah ini !
                                            </p>
                                            <p class="mb-0 " style="text-align: justify;">
                                                Kamu juga dapat memvalidasi keabsahan sertifikat kamu pada link
                                                <a target="_blank"
                                                    href="{{ url('/certificate/verification/') }}">{{ url('/certificate/verification/') }}</a>
                                            </p>
                                        </div>
                                        <a href="{{ route('customer.get-certificate-sertikom', ['category' => 'pelatihan', 'id' => Crypt::encrypt($participant->id), 'param' => 'kehadiran']) }}"
                                            class="mt-2 btn btn-sm btn-primary d-block d-md-inline-block">
                                            Unduh Sertifikat Kehadiran <i class="fa fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('customer.get-certificate-sertikom', ['category' => 'pelatihan', 'id' => Crypt::encrypt($participant->id), 'param' => 'pelatihan']) }}"
                                            class="mt-2 btn btn-sm btn-primary d-block d-md-inline-block">
                                            Unduh Sertifikat Pelatihan <i class="fa fa-file-pdf"></i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- End left right -->
                </div>
            </div>
        </div>
    </div>
@endsection
