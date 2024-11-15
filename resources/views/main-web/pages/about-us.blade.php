@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- About Start -->
    <section class="section" style="margin-top: 50px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0 wow animate__animated animate__fadeInLeft"
                    data-wow-delay=".1s">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $web->logo) }}" class="rounded img-fluid mx-auto d-block"
                            alt="{{ config('app.name') }} Logo" title="{{ config('app.name') }} Logo" loading="eager" />
                    </div>
                </div><!--end col-->

                <div class="col-lg-7 col-md-7 mt-4 pt-2 mt-sm-0 pt-sm-0 wow animate__animated animate__fadeInRight"
                    data-wow-delay=".1s">
                    <div class="section-title ms-lg-4">
                        <h1 class="fs-4 title mb-2 fw-bold" style="text-transform: uppercase; color: #0075B8;">
                            {{ $web->nama_bisnis }}
                        </h1>
                        <p class="text-muted" style="text-align: justify">
                            Selamat datang di {{ $web->nama_bisnis }}, Pusat Kegiatan Akademik yang menghadirkan inovasi
                            dan keunggulan di
                            bidang ICT dan Science. Kami bangga menjadi bagian dari perjalanan pendidikan Kamu, dengan
                            fokus untuk mencetak generasi yang siap bersaing di era digital.
                        </p>

                    </div>
                </div><!--end col-->
            </div><!--end row-->

        </div><!--end container-->

        <div class="container pt-2">
            <p class="text-muted wow animate__animated animate__fadeInRight" style="text-align: justify"
                data-wow-delay=".1s">
                {{ $web->nama_bisnis }} merupakan Pusat Kegiatan Akademik Bidang ICT dan Science Terbaik #1 di
                Indonesia,
                mengedepankan VI (6 angka Romawi) dan Star (Bintang dalam bahasa Inggris), yang melambangkan “6
                Bintang” di bidang Si: KompetenSi, KompetiSi, LiteraSi, OkupaSi, PrestaSi, dan SertifikaSi.
                Dengan pendekatan ini, kami berkomitmen untuk menciptakan lingkungan belajar yang kompetitif,
                inovatif, dan berorientasi pada prestasi.
            </p>
        </div>

        <div class="container pt-5">
            <div class="row justify-content-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                <div class="col-12 text-center">
                    <div class="section-title">
                        <h2 class="fs-5 mb-3">
                            Lokasi Alamat Kami
                        </h2>
                        <p class="text-muted mx-auto">
                            Berkantor pusat di Sumatera Utara, kami bangga menjadi bagian dari komunitas lokal sekaligus
                            menjangkau seluruh nusantara dengan layanan kami. Selain menyediakan fitur lengkap untuk
                            pelatihan, seminar, dan workshop eksklusif kami juga menyediakan pembahasan ujian tryout
                            berbayar yang dirancang untuk memberikan pengalaman belajar terbaik, Semua kegiatan ini didesain
                            khusus untuk membantu Kamu memahami setiap materi dengan lebih mendalam, memaksimalkan potensi,
                            dan meningkatkan peluang sukses.
                        </p>
                        <p class="text-muted">
                            Bersama {{ $web->nama_bisnis }}, persiapan karir Kamu lebih terarah, lebih efektif, dan tentu
                            saja lebih menyenangkan. Ayo, jadilah bagian dari mereka yang telah meraih prestasi
                            bersama kami!
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            <div class="row pt-4">
                <div class="col-lg-6 col-md-6 wow animate__animated animate__fadeInLeft" data-wow-delay=".1s">
                    <div class="card border-0 text-center features feature-primary feature-clean p-2">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-phone rounded h3 mb-0"></i>
                        </div>
                        <div class="content mt-4">
                            <h5 class="fw-bold">Telepon</h5>
                            <p class="text-muted">Jangan ragu untuk
                                menghubungi kami, kami selalu di sini untuk Kamu</p>
                            <a onclick="analyticsContactEvent({contact_type: 'phone', value: '{{ $web->kontak }}'})"
                                href="tel:{{ $web->kontak }}" class="read-more">
                                {{ $web->kontak }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 wow animate__animated animate__fadeInRight" data-wow-delay=".1s">
                    <div class="card border-0 text-center features feature-primary feature-clean p-2">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-envelope rounded h3 mb-0"></i>
                        </div>
                        <div class="content mt-4">
                            <h5 class="fw-bold">Email</h5>
                            <p class="text-muted">
                                Butuh solusi &#x3f; Kirimkan email Kamu dan kami akan segera merespon
                            </p>
                            <a onclick="analyticsContactEvent({contact_type: 'email', value: '{{ $web->email }}'})"
                                href="mailto:{{ $web->email }}" class="read-more">
                                {{ $web->email }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end container-->
    </section><!--end section-->
    <!-- About End -->
@endsection
@section('scripts')
    @php
        $breadcrumbItemListPosition = 0;
        $breadcrumbItemList = [
            [
                '@type' => 'ListItem',
                'position' => ++$breadcrumbItemListPosition,
                'name' => 'Home',
                'item' => route('mainweb.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => ++$breadcrumbItemListPosition,
                'name' => 'Tentang',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
