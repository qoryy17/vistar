@extends('customer-panel.layout.main')
@section('title', 'Vi Star | ' . $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $page_title }}</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Event</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">

                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- Row -->
                <div class="row sidemenu-height">
                    <div class="col-lg-8">

                        {{-- <div class="alert alert-warning mb-3" role="alert">
                            <h4 class="alert-heading"><i class="fa fa-info-circle"></i> Informasi !</h4>
                            <p style="text-align: justify;">
                                Seperti anda belum membeli paket tryout berbayar, untuk
                                menikmati
                                akses menyeluruh silahkan lakukan pembelian tryout berbayar.
                            </p>
                            <hr>
                            <p class="mb-0">
                                Atau anda juga bisa mencoba mode gratis tryout dengan mengklik link ini <a
                                    href="{{ route('site.tryout-gratis') }}" class="text-dark">"Coba Tryout Gratis"</a>
                            </p>
                        </div> --}}

                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Informasi Ujian</td>
                                                <td>Total Nilai</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <a href="#" title="Klik untuk melihat detil">
                                                        <h5 style="color: #0075B8;">Event-Exam-907997979 (CPNS)</h5>
                                                    </a>
                                                    <div>
                                                        Waktu Mulai : <b>{{ date('d-m-Y H:i:s') }}</b> | Selesai :
                                                        <b>{{ date('d-m-Y H:i:s') }}</b> <br>
                                                        Sisa Waktu : <b>10 Menit</b> <br>
                                                        Soal Benar : <b>70</b> | Soal Salah : <b>30</b> <br>
                                                        Soal Terjawab : <b>70</b> | Soal Tidak Terjawab : <b>0</b> <br>
                                                        <p class="badge bg-success mt-2">Created at :
                                                            {{ date('d-m-Y H:i:s') }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-1" style="white-space: normal; text-align:justify;">
                                                        Testimoni : Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                        Eligendi, incidunt reprehenderit enim, eveniet beatae expedita
                                                        laborum ut repellendus, cum deserunt doloremque id. Labore mollitia
                                                        porro nihil tenetur quia. Tempore, magnam?
                                                        <br>
                                                        Rating : <i class="fa fa-star"
                                                            style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                    </div>
                                                </td>
                                                <td style="text-align: center;">
                                                    <h1>500</h1>
                                                    Keterangan : <span class="badge bg-success">Lulus</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        {{-- Informasi Paket Tryout --}}
                        <div class="card custom-card">
                            <div class="card-body">
                                <div>
                                    <h6>Informasi Event Paket</h6>
                                    <h6 style="padding: 0px; margin:0px;" class="mb-2"><span class="fs-30 me-2"
                                            style="color: #0075B8;">Tryout CPNS</span><span class="badge bg-success">Rp.
                                            50.000</span>
                                    </h6>
                                    <span class="text-muted tx-12">
                                        Event berlangsung tanggal {{ date('d-m-Y') }} sampai {{ date('d-m-Y') }} <br>
                                        Klik tombol dibawah untuk melanjutkan ujian Tryout berbasis CBT/CAT
                                    </span>
                                    <form action="{{ route('ujian.main', ['param' => Crypt::encrypt('berbayar')]) }}"
                                        method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-block btn-default mt-2 btn-web"><i
                                                class="fa fa-check-circle"></i>
                                            Mulai Ujian</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card">
                            <div class="card-body">
                                <div>
                                    <h6>Informasi Event Paket</h6>
                                    <h6 style="padding: 0px; margin:0px;" class="mb-2"><span class="fs-30 me-2"
                                            style="color: #0075B8;">Tryout PPPK</span><span class="badge bg-success">Rp.
                                            50.000</span>
                                    </h6>
                                    <span class="text-muted tx-12">
                                        Event berlangsung tanggal {{ date('d-m-Y') }} sampai {{ date('d-m-Y') }} <br>
                                        Klik tombol dibawah untuk melanjutkan ujian Tryout berbasis CBT/CAT
                                    </span>
                                    <a href="{{ url('/') }}" class="btn btn-block btn-default mt-2 btn-web"><i
                                            class="fa fa-check-circle"></i> Mulai Ujian</a>
                                </div>
                            </div>
                        </div>
                        <div class="card custom-card">
                            <div class="card-body">
                                <div>
                                    <h6>Informasi Event Paket</h6>
                                    <h6 style="padding: 0px; margin:0px;" class="mb-2"><span class="fs-30 me-2"
                                            style="color: #0075B8;">Tryout Kedinasan</span><span
                                            class="badge bg-success">Rp.
                                            50.000</span>
                                    </h6>
                                    <span class="text-muted tx-12">
                                        Klik tombol dibawah untuk melanjutkan ujian Tryout berbasis CBT/CAT
                                    </span>
                                    <a href="{{ url('/') }}" class="btn btn-block btn-default mt-2 btn-web"><i
                                            class="fa fa-check-circle"></i> Mulai Ujian</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->

            </div>
        </div>
    </div>
@endsection
