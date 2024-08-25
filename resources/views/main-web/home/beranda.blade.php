@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 border-bottom d-table w-100" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-7">
                    <div class="title-heading mt-4">
                        {{-- <div class="alert alert-pills shadow text-dark" role="alert">
                            <span class="badge rounded-pill bg-danger me-1">v4.8.0</span>
                            <span class="content"> Build <span class="text-primary">anything</span> you want -
                                Landrick.</span>
                        </div> --}}
                        <h1 class="heading mb-3">Vi Star Indonesia <br> Center Of Visi <br><span style="color: #0075B8;"
                                class="text-primary typewrite" data-period="2000"
                                data-type='["Tryout CPNS", "Tryout PPPK", "Tryout Kedinasan"]'>
                                <span class="wrap"></span> </span> </h1>
                        <p class="para-desc text-muted">Ujian Tryout untuk CPNS, PPPK dan Kedinasan Terpercaya Seluruh
                            Indonesia, Raih Mimpimu Menjadi ASN Bersama Kami Di Vi Star Indonesia</p>
                        <div class="mt-4">
                            <a href="#produk" class="btn btn-primary btn-pills"><i class="uil uil-arrow-down"></i>
                                Mulai Jelajah</a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-6 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0">
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

    <x-web.section-feature />

    <!-- Product Start -->
    <section class="section" id="produk">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Pilih Paket Ujian Tryout</h4>
                        <p class="text-muted para-desc mb-0 mx-auto">Temukan paket ujian tryout yang sempurna untuk Anda!
                            Dengan berbagai pilihan paket yang dirancang sesuai kebutuhan, <span
                                class="text-primary fw-bold">Vi Star Indonesia</span> memberikan
                            solusi terbaik untuk persiapan ujian Anda.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                        <div class="card-body p-0">
                            <span
                                class="py-2 px-4 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">PPPK</span>
                            <h2 class="fw-bold mb-0 mt-3"><sup><small>Mulai dari</small></sup> Rp 100.000</h2>
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
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Akses Bagikan Referal
                                </li>
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
                                class="text-center d-block shadow small h6">Best</span></div>
                        <div class="card-body p-0">
                            <span
                                class="py-2 px-4 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">CPNS</span>
                            <h2 class="fw-bold mb-0 mt-3"><sup><small>Mulai dari</small></sup> Rp 100.000</h2>
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
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Akses Bagikan Referal
                                </li>
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
                            <h2 class="fw-bold mb-0 mt-3"><sup><small>Mulai dari</small></sup> Rp 100.000</h2>
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
                                <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                        <i class="uil uil-check-circle align-middle"></i></span>Akses Bagikan Referal
                                </li>
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

        <!-- Coba Gratis End -->
        <div class="container mt-100 mt-60">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section-title">
                        <h4 class="title mb-4">Uji Coba Tryout Gratis <br> <span class="text-primary">Vi Star
                                Indonesia</span>
                        </h4>
                        <p class="text-muted para-desc" style="text-align: justify;">Tunggu Kesempatan Apalagi, Segera
                            Daftarkan Diri Anda Untuk
                            Mengikuti Uji Coba Tryout Gratis Dengan 1x Kesempatan Ujian Dan Lihat Hasil Yang Anda Dapatkan
                        </p>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-1"><span class="text-primary h5 me-2">
                                    <i class="uil uil-check-circle align-middle"></i></span>Bagikan Informasi Produk Kami,
                                Kepada Keluarga/Kerabat Anda
                            </li>
                            <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>Dapatkan Uji Coba 1x</li>
                            <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>Lihat Hasil Ujian Anda</li>
                        </ul>
                        <a href="{{ route('mainweb.daftar-tryout-gratis') }}" class="btn btn-primary btn-pills">Daftar
                            Sekarang</a>
                    </div>
                </div><!--end col-->

                <div class="col-lg-6">
                    <div class="row ms-lg-5" id="counter">
                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-12 mt-4 mt-lg-0 pt-2 pt-lg-0">
                                    <div class="card counter-box border-0 bg-light bg-gradient shadow text-center rounded">
                                        <div class="card-body py-5">
                                            <h2 class="mb-0"><span class="counter-value" data-target="100">1</span>+
                                            </h2>
                                            <h5 class="counter-head mb-0">Peserta Gratis</h5>
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-12 mt-4 pt-2">
                                    <div
                                        class="card counter-box border-0 bg-primary bg-gradient shadow text-center rounded">
                                        <div class="card-body py-5">
                                            <h2 class="text-white mb-0"><span class="counter-value"
                                                    data-target="200">1</span>+</h2>
                                            <h5 class="counter-head mb-0 text-white">Tryout CPNS Gratis</h5>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div><!--end Row-->
                        </div><!--end col-->

                        <div class="col-md-6 col-12">
                            <div class="row pt-lg-4 mt-lg-4">
                                <div class="col-12 mt-4 pt-2">
                                    <div
                                        class="card counter-box border-0 bg-success bg-gradient shadow text-center rounded">
                                        <div class="card-body py-5">
                                            <h2 class="text-white mb-0"><span class="counter-value"
                                                    data-target="10">0</span>+</h2>
                                            <h5 class="counter-head mb-0 text-white">Tryout PPPK Gratis</h5>
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-12 mt-4 pt-2">
                                    <div class="card counter-box border-0 bg-light bg-gradient shadow text-center rounded">
                                        <div class="card-body py-5">
                                            <h2 class="mb-0"><span class="counter-value" data-target="200">1</span>+
                                            </h2>
                                            <h5 class="counter-head mb-0">Tryout Kedinasan Gratis</h5>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div><!--end Row-->
                        </div><!--end col-->
                    </div><!--end Row-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <div class="container mt-100 mt-60">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Testimoni Dari Peserta</h4>
                        <p class="text-muted para-desc mx-auto mb-0">Dengarkan cerita sukses dari mereka yang telah
                            merasakan manfaat Tryout <span class="text-primary fw-bold">Vi Star Indonesia</span>! Para
                            peserta kami telah berhasil meningkatkan
                            persiapan ujian mereka dengan fitur-fitur unggulan dan soal-soal terbaru yang kami tawarkan.
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row justify-content-center">
                <div class="col-lg-12 mt-4">
                    <div class="tiny-three-item">
                        @foreach ($testimoni->get() as $testimoniPeserta)
                            <div class="tiny-slide">
                                <div class="d-flex client-testi m-2">
                                    <img src="{{ asset('storage/user/' . $testimoniPeserta->foto) }}"
                                        class="avatar avatar-small client-image rounded shadow" alt="">
                                    <div class="card flex-1 content p-3 shadow rounded position-relative">
                                        <ul class="list-unstyled mb-0">
                                            @for ($i = 0; $i < $testimoniPeserta->rating; $i++)
                                                <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i>
                                                </li>
                                            @endfor
                                        </ul>
                                        <p class="text-muted mt-2">" {{ $testimoniPeserta->testimoni }} "</p>
                                        <h6 class="text-primary">
                                            {{ $testimoniPeserta->nama_lengkap }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $testimoniPeserta->pendidikan }} - {{ $testimoniPeserta->jurusan }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Counter End -->
@endsection
