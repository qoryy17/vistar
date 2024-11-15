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
                                <a href="{{ route('customer.get-sertikom', ['category' => 'seminar']) }}">Seminar
                                </a>
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
                        <div class="card overflow-hidden custom-card">
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
                                <a href="{{ route('customer.get-sertikom', ['category' => 'seminar']) }}"
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <h5>Jadwal Seminar</h5>
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
                                                        Rekaman Seminar
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
                                        Informasi seminar
                                    </h5>
                                    <div class="alert alert-primary" role="alert" style="text-align: justify;">
                                        Saat ini kamu dalam proses pendaftaran peserta seminar
                                        "<strong>{{ $sertikom->produk }}</strong>",
                                        mohon untuk dapat menunggu dan
                                        memantau dashboard ini ya...
                                    </div>
                                @else
                                    @if ($currentStep->tahapan == 'Proses')
                                        <h5 class="mb-3">
                                            Informasi seminar
                                        </h5>
                                        <div class="alert alert-warning" role="alert" style="text-align: justify;">
                                            Saat ini seminar sedang berlangsung. Sertifikat kehadiran akan tersedia diunduh
                                            setelah seminar telah dilaksanakan !
                                        @elseif ($currentStep->tahapan == 'Selesai')
                                            <div class="alert alert-success mb-0" role="alert">
                                                <h5 class="alert-heading">Unduh Sertifikat Kamu</h5>
                                                <p>
                                                    Selamat kamu telah selesai mengikuti seminar
                                                    "{{ $sertikom->produk }}".
                                                    Untuk selanjutnya kamu dapat mengunduh sertikat dibawah ini !
                                                </p>
                                                <p class="mb-0 " style="text-align: justify;">
                                                    Kamu juga dapat memvalidasi keabsahan sertifikat kamu pada link
                                                    <a target="_blank"
                                                        href="{{ url('/certificate/verification/') }}">{{ url('/certificate/verification/') }}</a>
                                                </p>
                                            </div>
                                            <a href="{{ route('customer.get-certificate-sertikom', ['category' => 'seminar', 'id' => Crypt::encrypt($participant->id), 'param' => 'null']) }}"
                                                class="mt-2 btn btn-sm btn-primary d-block d-md-inline-block">
                                                <i class="fa fa-file-pdf"></i> Unduh Sertifikat Kehadiran
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
