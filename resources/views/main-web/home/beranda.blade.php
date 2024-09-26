@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Hero Start -->
    <section class="bg-half-100 d-table w-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-7">
                    <div class="title-heading mt-4 d-flex flex-column gap-3">
                        <div class="heading text d-flex flex-column">
                            <h1 class="d-flex flex-column">
                                <span class="fw-bold">{{ $web->nama_bisnis }}</span>
                                <span class="fs-4">{{ $web->tagline }}</span>
                            </h1>
                        </div>
                        <div style="color: #0075B8; overflow-y: hidden !important;" class="py-2 fs-1 text-primary typewrite"
                            data-period="2000" data-type='["Tryout CPNS", "Tryout PPPK", "Tryout Kedinasan"]'>
                        </div>
                        <h2 class="para-desc text-muted" style="text-align: justify">
                            {{ $web->nama_bisnis }} merupakan Pusat Kegiatan Akademik Bidang ICT dan Science Terbaik
                            &num;1 di Indonesia dengan mengedepankan VI (6 angka Romawi) dan Star (Bintang dalam Inggris) “6
                            Bintang” dibidang
                            <span class="text-primary fw-bold">Si</span>
                            yaitu :
                            Kompeten<span class="text-primary fw-bold">Si</span>,
                            Kompeti<span class="text-primary fw-bold">Si</span>,
                            Litera<span class="text-primary fw-bold">Si</span>,
                            Okupa<span class="text-primary fw-bold">Si</span>,
                            Presta<span class="text-primary fw-bold">Si</span>,
                            dan
                            Sertifika<span class="text-primary fw-bold">Si</span>
                        </h2>
                        <div class="mt-4">
                            <a href="#produk" class="btn btn-primary btn-pills"><i class="uil uil-arrow-down"></i>
                                Mulai Jelajah</a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0" data-wow-delay=".1s">
                    <div class="position-relative">
                        <img width="400" src="{{ url('resources/images/model1.png') }}"
                            class="rounded img-fluid mx-auto d-block" alt="Banner Model 1 {{ config('app.name') }}"
                            title="Model Banner {{ config('app.name') }}" loading="eager" />
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Feature Start -->
    <section class="section">

        <!-- Product Start -->
        @if (count($productCategories) > 0)
            <div class="container pb-5" id="produk">
                <div class="row justify-content-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <div class="col-12 text-center">
                        <div class="section-title mb-4 pb-2">
                            <h2 class="title mb-4">Bidang Kompetensi Pilih Paket Ujian</h2>
                            <p class="text-muted para-desc mb-0 mx-auto">
                                Temukan paket ujian tryout yang sempurna untuk Anda! Dengan berbagai pilihan paket yang
                                dirancang sesuai kebutuhan, <span
                                    class="text-primary fw-bold">{{ $web->nama_bisnis }}</span> memberikan
                                solusi terbaik untuk persiapan ujian Anda.
                            </p>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    @foreach ($productCategories as $category)
                        <div class="col-lg-4 col-md-6 mt-4 pt-2">
                            <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                                <div class="card-body p-0">
                                    @if ($category['is_popular'])
                                        <div class="ribbon ribbon-right ribbon-warning overflow-hidden">
                                            <span class="text-center d-block shadow small h6">
                                                Populer
                                            </span>
                                        </div>
                                    @endif
                                    <h3
                                        class="py-2 px-4 d-inline-block bg-soft-{{ $category['is_popular'] ? 'warning' : 'primary' }} h6 mb-0 text-{{ $category['is_popular'] ? 'warning' : 'primary' }} rounded-lg">
                                        Kategori : {{ $category['title'] }}
                                    </h3>
                                    <p class="fs-3 fw-bold mb-0 mt-3">
                                        <span class="text-nowrap">Paket Tryout {{ $category['title'] }}</span>
                                    </p>
                                    <p class="text-muted">Sekali Beli</p>
                                    <p class="text-muted">Fitur yang anda dapatkan dalam paket ini</p>

                                    <ul class="list-unstyled pt-3 border-top">
                                        @foreach ($category['features'] as $feature)
                                            <li class="h6 text-muted mb-0">
                                                <span class="icon h5 me-2">
                                                    <i class="uil uil-check-circle align-middle"></i>
                                                </span>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-4">
                                        <div class="d-grid">
                                            <a href="{{ route('mainweb.product', ['category_id' => $category['id']]) }}"
                                                class="btn btn-pills {{ $category['is_popular'] ? 'btn-warning' : 'btn-primary' }}">
                                                Lihat Produk Tryout <i class="mdi mdi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                    @endforeach
                </div><!--end row-->
            </div><!--end container-->
        @endif

        <x-web.container-coba-gratis />

        <x-web.container-produk-unggulan />

        <x-web.container-testimoni />

        <x-web.container-counter-perserta />
    </section><!--end section-->
    <!-- End feature -->
@endsection
@section('styles')
    <!-- Tiny Slider -->
    <link href="{{ asset('resources/web/dist/assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
@endsection
@section('scripts-top')
    <!-- Tiny Slider -->
    <script src="{{ asset('resources/web/dist/assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
@endsection
