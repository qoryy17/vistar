@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 border-bottom d-table w-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-7 wow animate__animated animate__fadeInLeft" data-wow-delay=".1s">
                    <div class="title-heading mt-4">

                        <h1 class="heading text mb-3"><span class="fw-bold">{{ $web->nama_bisnis }}</span><br>
                            <span class="fs-2">{{ $web->tagline }}</span> <br>
                            <span style="color: #0075B8;" class="text-primary typewrite" data-period="2000"
                                data-type='["Tryout CPNS", "Tryout PPPK", "Tryout Kedinasan"]'>
                                <span class="wrap"></span>
                            </span>
                        </h1>
                        <p class="para-desc text-muted" style="text-align: justify">{{ $web->nama_bisnis }} merupakan Pusat
                            Kegiatan Akademik
                            Bidang ICT dan Science
                            Terbaik #1 di Indonesia dengan
                            mengedepankan VI (6 angka Romawi) dan Star (Bintang dalam Inggris) “6 Bintang” dibidang <span
                                class="text-primary fw-bold">Si</span> yaitu
                            :
                            Kompeten<span class="text-primary fw-bold">Si</span>, Kompeti<span
                                class="text-primary fw-bold">Si</span>, Litera<span class="text-primary fw-bold">Si</span>,
                            Okupa<span class="text-primary fw-bold">Si</span>, Presta<span
                                class="text-primary fw-bold">Si</span>,
                            dan
                            Sertifika<span class="text-primary fw-bold">Si</span>
                        </p>
                        <div class="mt-4">
                            <a href="#produk" class="btn btn-primary btn-pills"><i class="uil uil-arrow-down"></i>
                                Mulai Jelajah</a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0 wow animate__animated animate__fadeInUp"
                    data-wow-delay=".1s">
                    <div class="position-relative">
                        <img src="{{ url('resources/images/model1.png') }}" class="rounded img-fluid mx-auto d-block"
                            alt="">
                        {{-- <div class="play-icon">
                            <a href="#!" data-type="youtube" data-id="yba7hPeTSjk" class="play-btn lightbox border-0">
                                <i class="mdi mdi-play text-primary rounded-circle shadow"></i>
                            </a>
                        </div> --}}
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Product Start -->
    <section class="section" id="produk">
        <div class="container">
            <div class="row justify-content-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Bidang Kompetensi Pilih Paket Ujian</h4>
                        <p class="text-muted para-desc mb-0 mx-auto">Temukan paket ujian tryout yang sempurna untuk Anda!
                            Dengan berbagai pilihan paket yang dirancang sesuai kebutuhan, <span
                                class="text-primary fw-bold">{{ $web->nama_bisnis }}</span> memberikan
                            solusi terbaik untuk persiapan ujian Anda.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                        <div class="card-body p-0">
                            <span
                                class="py-2 px-4 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">PPPK</span>
                            <h2 class="fw-bold mb-0 mt-3"><sup><small>Mulai dari</small></sup>
                                {{ Number::currency(50000, in: 'IDR') }}</h2>
                            <p class="text-muted">Sekali Beli</p>

                            <p class="text-muted">Fitur yang anda dapatkan dalam paket ini</p>

                            <ul class="list-unstyled pt-3 border-top">
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Ujian Tidak Terbatas
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Hasil Ujian
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Grafik Hasil Ujian
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Review Pembahasan Soal
                                </li>
                                {{-- <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Akses Bagikan Referal
                                </li> --}}
                            </ul>

                            <div class="mt-4">
                                <div class="d-grid">
                                    <a href="{{ route('mainweb.search-produk-berbayar', ['paketTryout=PPPK&cariPaket=']) }}"
                                        class="btn btn-pills btn-primary">Beli Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                        <div class="ribbon ribbon-right ribbon-warning overflow-hidden"><span
                                class="text-center d-block shadow small h6">Populer</span></div>
                        <div class="card-body p-0">
                            <span
                                class="py-2 px-4 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">CPNS</span>
                            <h2 class="fw-bold mb-0 mt-3"><sup><small>Mulai dari</small></sup>
                                {{ Number::currency(50000, in: 'IDR') }}</h2>
                            <p class="text-muted">Sekali Beli</p>

                            <p class="text-muted">Fitur yang anda dapatkan dalam paket ini</p>

                            <ul class="list-unstyled pt-3 border-top">
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Ujian Tidak Terbatas
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Hasil Ujian
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Grafik Hasil Ujian
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Review Pembahasan Soal
                                </li>
                                {{-- <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Akses Bagikan Referal
                                </li> --}}
                            </ul>

                            <div class="mt-4">
                                <div class="d-grid">
                                    <a href="{{ route('mainweb.search-produk-berbayar', ['paketTryout=CPNS&cariPaket=']) }}"
                                        class="btn btn-pills btn-primary">Beli Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                        <div class="card-body p-0">
                            <span
                                class="py-2 px-4 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">Kedinasan</span>
                            <h2 class="fw-bold mb-0 mt-3"><sup><small>Mulai dari</small></sup>
                                {{ Number::currency(50000, in: 'IDR') }}</h2>
                            <p class="text-muted">Sekali Beli</p>

                            <p class="text-muted">Fitur yang anda dapatkan dalam paket ini</p>

                            <ul class="list-unstyled pt-3 border-top">
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Ujian Tidak Terbatas
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Hasil Ujian
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Grafik Hasil Ujian
                                </li>
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Review Pembahasan Soal
                                </li>
                                {{-- <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Akses Bagikan Referal
                                </li> --}}
                            </ul>

                            <div class="mt-4">
                                <div class="d-grid">
                                    <a href="{{ route('mainweb.search-produk-berbayar', ['paketTryout=Kedinasan&cariPaket=']) }}"
                                        class="btn btn-pills btn-primary">Beli Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!-- Price End -->

        <!-- Coba Gratis -->
        <x-web.container-coba-gratis />

        <!-- Keunggulan -->
        <x-web.container-produk-unggulan />

    </section><!--end section-->
    <!-- Counter End -->

    <!-- Feature Start -->
    <section class="section">

        <!-- Testimoni Peserta -->
        <x-web.container-testimoni />

        <!-- Counter Peserta -->
        <x-web.container-counter-perserta />
        <!-- End Counter Customer -->
    </section><!--end section-->
    <!-- End feature -->
@endsection
