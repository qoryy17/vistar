@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Start Kontak -->
    <section class="section" class="bg-half-170 bg-light d-table w-100"
        style="background: url('{{ asset('resources/web/dist/assets/contact-detail.jpg') }}') center center;">
        <div class="bg-overlay bg-overlay-white"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h1 class="title mb-0">Hubungi {{ config('app.name') }}</h1>
                    </div>
                </div> <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6  mt-4 pt-2">
                    <div class="card shadow rounded border-0">
                        <div class="card-body py-5">
                            <h2 class="fs-4 card-title text-primary">Informasi Kontak</h2>
                            <p>
                                {{ $web->nama_bisnis }} Siap memenuhi semua kebutuhan dan menjawab pertanyaan Anda! Cek
                                informasi kontak kami
                                di bawah ini dan hubungi kami sekarang !
                            </p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-8 col-md-6 ps-md-3 pe-md-3 mt-4 pt-2">
                    <div class="card map map-height-two rounded map-gray border-0">
                        <div class="card-body p-0">
                            <iframe title="Map {{ config('app.name') }}"
                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15928.74936941584!2d98.6726273!3d3.5442179!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303131128e219459%3A0xc4804750f528b3ff!2sVistar%20Indonesia!5e0!3m2!1sen!2sid!4v1727570591222!5m2!1sen!2sid"
                                class="w-100 mw-100" width="600" height="450" style="border:0;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <div class="container mt-100 mt-60">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 text-center features feature-primary feature-clean p-4">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-phone rounded h3 mb-0"></i>
                        </div>
                        <div class="content mt-4">
                            <h3 class="fs-5 fw-bold">Telepon</h3>
                            <p class="text-muted">
                                Jangan ragu untuk menghubungi kami, kami selalu di sini untuk Anda
                            </p>
                            <a onclick="analyticsContactEvent({contact_type: 'phone', value: '{{ $web->kontak }}'})"
                                href="tel:{{ $web->kontak }}" class="read-more">
                                {{ $web->kontak }}
                            </a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="card border-0 text-center features feature-primary feature-clean p-4">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-envelope rounded h3 mb-0"></i>
                        </div>
                        <div class="content mt-4">
                            <h3 class="fs-5 fw-bold">Email</h3>
                            <p class="text-muted">
                                Butuh solusi &#x3f; Kirimkan email Anda dan kami akan segera merespon
                            </p>
                            <a onclick="analyticsContactEvent({contact_type: 'email', value: '{{ $web->email }}'})"
                                href="mailto:{{ $web->email }}" class="read-more">
                                {{ $web->email }}
                            </a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="card border-0 text-center features feature-primary feature-clean p-4">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-map-marker rounded h3 mb-0"></i>
                        </div>
                        <div class="content mt-4">
                            <h3 class="fs-5 fw-bold">Alamat</h3>
                            <p class="text-muted">{{ $web->alamat }}</p>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End contact -->
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
                'name' => 'Kontak',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
