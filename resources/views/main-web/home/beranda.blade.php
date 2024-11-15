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
                            data-period="2000"
                            data-type='["Pelatihan" ,"Seminar", "Workshop", "Sertifikasi","Tryout Simulasi CAT CPNS", "Tryout Simulasi CAT PPPK", "Tryout Simulasi CAT Kedinasan"]'>
                        </div>
                        <h2 class="para-desc text-muted" style="text-align: justify; font-weight: normal;">
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
                            <a href="#explore" class="btn btn-soft-primary btn-pills">
                                <i class="uil uil-arrow-down"></i>
                                Jelajahi Sekarang
                            </a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0" data-wow-delay=".1s">
                    <div class="position-relative">
                        <img src="{{ url('resources/images/model-8.png') }}" class="rounded img-fluid mx-auto d-block"
                            alt="Banner Model 1 {{ config('app.name') }}" title="Model Banner {{ config('app.name') }}"
                            loading="eager" />
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Training and Certification, Workshop -->
    <section class="section" id="explore" style="margin-bottom: 0; padding-bottom: 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-4 pb-2">
                        <h4 class="title mb-4">
                            Tingkatkan Karier IT Kamu dengan Pelatihan, Seminar dan Workshop
                        </h4>
                        <p class="text-muted mb-0 mx-auto">
                            Siap untuk UPGRADE? Dapatkan skill IT yang relevan dan bersertifikat hanya di <span
                                class="text-primary fw-bold">{{ $web->nama_bisnis }}</span>.
                            Ikuti berbagai pelatihan, seminar dan workshop yang dirancang khusus untuk meningkatkan
                            kompetensi Kamu .
                            Dengan pembelajaran virtual interaktif, Kamu bisa belajar kapan saja, di mana saja, dengan
                            materi yang menarik dan harga yang terjangkau.
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            @if (!$productTraining->isEmpty())
                <!-- Training Center -->
                <div class="row wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <div class="col-lg-4 col-md-6 col-12 d-none d-sm-block">
                        <img width="400" src="{{ url('resources/images/model-2.png') }}" class="rounded mx-auto d-block"
                            alt="Banner Model 1 {{ config('app.name') }}" title="Model Banner {{ config('app.name') }}"
                            loading="eager" />
                    </div>
                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="d-flex justify-content-between ">
                            <h3>
                                Pelatihan Online
                            </h3>
                            <a href="{{ route('mainweb.product-sertikom', ['category' => 'pelatihan']) }}"
                                class="btn btn-md btn-soft-primary btn-pills">
                                Lihat Semua
                                <i class="uil uil-angle-right"></i>
                            </a>
                        </div>
                        <div class="row">
                            @foreach ($productTraining as $trainingItem)
                                @php
                                    $features = [];

                                    $url = route('mainweb.product-sertikom.training-seminar.show', [
                                        'feature' => \App\Enums\FeatureEnum::TRAINING->value,
                                        'id' => $trainingItem->id,
                                    ]);
                                    $visitorTraining = \App\Models\Sertikom\VisitorProdukModel::where(
                                        'ref_produk_id',
                                        $trainingItem->id,
                                    )->count();
                                    $image = asset('storage/' . $trainingItem->thumbnail);
                                @endphp
                                <div class="col-lg-6 col-md-6 col-12 mt-4 pt-2">
                                    <div class="card blog blog-primary rounded border-0 shadow overflow-hidden" itemscope
                                        itemtype="https://schema.org/Product"itemscope
                                        itemtype="https://schema.org/Product">
                                        <div class="position-relative">
                                            <img itemprop="image" src="{{ $image }}" class="card-img-top"
                                                alt="Thumbnail {{ $image }}" title="Thumbnail {{ $image }}"
                                                loading="eager">
                                            <div class="overlay"></div>
                                            <div class="teacher d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="mb-0">
                                                        <a href="javascript:void(0)"
                                                            class="user">{{ $trainingItem->instruktur }}</a>
                                                    </h6>
                                                    <p class="small my-0 profession">Instruktur</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="position-relative">
                                            <div class="shape overflow-hidden text-color-white">
                                                <svg viewBox="0 0 2880 48" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z"
                                                        fill="currentColor">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="card-body content">
                                            <span class="badge bg-primary mb-2">
                                                <i class="mdi mdi-calendar-range"></i>
                                                {{ \Carbon\Carbon::parse($trainingItem->tanggal_mulai)->translatedFormat('F') }}
                                            </span>
                                            <h6>
                                                <a href="#" class="text-primary">{{ $trainingItem->topik }}</a>
                                            </h6>
                                            <a itemprop="url" href="{{ $url }}" class="title text-dark h5">
                                                <span itemprop="name">{{ $trainingItem->produk }}</span>
                                            </a>
                                            <p class="text-muted mt-2" style="text-align: justify">
                                                <span class="d-flex fs-5 fw-bold text-primary" itemscope itemprop="offers"
                                                    itemtype="https://schema.org/Offer">
                                                    <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
                                                    <span itemprop="priceCurrency" content="IDR">Rp.</span>
                                                    <span itemprop="price" content="{{ $trainingItem->harga }}">
                                                        {{ Number::Format($trainingItem->harga) }}
                                                    </span>
                                                </span>
                                            </p>
                                            <ul
                                                class="list-unstyled d-flex justify-content-between border-top mt-3 pt-3 mb-0">
                                                <li class="text-muted small"><i class="uil uil-book-open text-info"></i>
                                                    Online
                                                </li>
                                                <li class="text-muted small ms-3"><i class="uil uil-eye text-primary"></i>
                                                    {{ $visitorTraining }}
                                                </li>
                                            </ul>

                                            <meta itemprop="description" content="{{ $trainingItem->deskripsi }}" />

                                            {{--  IDEA: get testimoni data from user testimoni, currently set to manual  --}}
                                            <div itemscope itemprop="aggregateRating"
                                                itemtype="https://schema.org/AggregateRating">
                                                <meta itemprop="ratingValue" content="5" />
                                                <meta itemprop="reviewCount"
                                                    content="{{ substr(strval($trainingItem->id), -2) + substr(strval($trainingItem->id), 0, 2) }}" />
                                            </div>
                                            <div itemscope itemprop="review" itemtype="https://schema.org/Review">
                                                <div itemscope itemprop="author" itemtype="https://schema.org/Person">
                                                    <meta itemprop="name" content="Qori Chairawan" />
                                                </div>
                                                <meta itemprop="datePublished"
                                                    content="{{ \Carbon\Carbon::parse($trainingItem->created_at)->addDays(2)->format('Y-m-d') }}" />
                                                <div itemscope itemprop="reviewRating"
                                                    itemtype="https://schema.org/Rating">
                                                    <meta itemprop="worstRating" content="4" />
                                                    <meta itemprop="ratingValue" content="5" />
                                                    <meta itemprop="bestRating" content="5" />
                                                </div>
                                                <meta itemprop="reviewBody"
                                                    content="Pelatihan berbasis online terbaik." />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div><!--end row-->
            @endif

            @if (!$productSeminar->isEmpty())
                <!-- Seminar Center -->
                <div class="row wow animate__animated animate__fadeInUp" data-wow-delay=".1s" style="margin-top: 10vh;">
                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="d-flex justify-content-between">
                            <h3>
                                Seminar Online
                            </h3>
                            <a href="{{ route('mainweb.product-sertikom', ['category' => 'seminar']) }}"
                                class="btn btn-md btn-soft-primary btn-pills">
                                Lihat Semua
                                <i class="uil uil-angle-right"></i>
                            </a>
                        </div>
                        <div class="row">
                            @foreach ($productSeminar as $seminarItem)
                                @php
                                    $features = [];

                                    $url = route('mainweb.product-sertikom.training-seminar.show', [
                                        'feature' => \App\Enums\FeatureEnum::SEMINAR->value,
                                        'id' => $seminarItem->id,
                                    ]);
                                    $visitorSeminar = \App\Models\Sertikom\VisitorProdukModel::where(
                                        'ref_produk_id',
                                        $seminarItem->id,
                                    )->count();
                                    $image = asset('storage/' . $seminarItem->thumbnail);
                                @endphp
                                <div class="col-lg-6 col-md-6 col-12 mt-4 pt-2">
                                    <div class="card blog blog-primary rounded border-0 shadow overflow-hidden" itemscope
                                        itemtype="https://schema.org/Product"itemscope
                                        itemtype="https://schema.org/Product">
                                        <div class="position-relative">
                                            <img itemprop="image" src="{{ $image }}" class="card-img-top"
                                                alt="Thumbnail {{ $image }}"
                                                title="Thumbnail {{ $image }}" loading="eager">
                                        </div>
                                        <div class="card-body content">
                                            <span class="badge bg-primary mb-2">
                                                <i class="mdi mdi-calendar-range"></i>
                                                {{ \Carbon\Carbon::parse($seminarItem->tanggal_mulai)->translatedFormat('F') }}
                                            </span>
                                            <h6>
                                                <a href="#" class="text-primary">{{ $seminarItem->topik }}</a>
                                            </h6>
                                            <a itemprop="url" href="{{ $url }}" class="title text-dark h5">
                                                <span itemprop="name">{{ $seminarItem->produk }}</span>
                                            </a>
                                            <p class="text-muted mt-2" style="text-align: justify">
                                                <span class="d-flex fs-5 fw-bold text-primary" itemscope itemprop="offers"
                                                    itemtype="https://schema.org/Offer">
                                                    <meta itemprop="availability"
                                                        content="https://schema.org/OnlineOnly" />
                                                    <span itemprop="priceCurrency" content="IDR">Rp.</span>
                                                    <span itemprop="price" content="{{ $seminarItem->harga }}">
                                                        {{ Number::Format($seminarItem->harga) }}
                                                    </span>
                                                </span>
                                            </p>
                                            <ul
                                                class="list-unstyled d-flex justify-content-between border-top mt-3 pt-3 mb-0">
                                                <li class="text-muted small"><i class="uil uil-book-open text-info"></i>
                                                    Online
                                                </li>
                                                <li class="text-muted small ms-3"><i class="uil uil-eye text-primary"></i>
                                                    {{ $visitorSeminar }}
                                                </li>
                                            </ul>

                                            <meta itemprop="description" content="{{ $seminarItem->deskripsi }}" />

                                            {{--  IDEA: get testimoni data from user testimoni, currently set to manual  --}}
                                            <div itemscope itemprop="aggregateRating"
                                                itemtype="https://schema.org/AggregateRating">
                                                <meta itemprop="ratingValue" content="5" />
                                                <meta itemprop="reviewCount"
                                                    content="{{ substr(strval($seminarItem->id), -2) + substr(strval($seminarItem->id), 0, 2) }}" />
                                            </div>
                                            <div itemscope itemprop="review" itemtype="https://schema.org/Review">
                                                <div itemscope itemprop="author" itemtype="https://schema.org/Person">
                                                    <meta itemprop="name" content="Qori Chairawan" />
                                                </div>
                                                <meta itemprop="datePublished"
                                                    content="{{ \Carbon\Carbon::parse($seminarItem->created_at)->addDays(2)->format('Y-m-d') }}" />
                                                <div itemscope itemprop="reviewRating"
                                                    itemtype="https://schema.org/Rating">
                                                    <meta itemprop="worstRating" content="4" />
                                                    <meta itemprop="ratingValue" content="5" />
                                                    <meta itemprop="bestRating" content="5" />
                                                </div>
                                                <meta itemprop="reviewBody" content="Seminar berbasis online terbaik." />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 d-none d-sm-block">
                        <img width="520" src="{{ url('resources/images/model-7.png') }}"
                            class="rounded mt-5 mx-auto d-block" alt="Banner Model 1 {{ config('app.name') }}"
                            title="Model Banner {{ config('app.name') }}" loading="eager" />
                    </div>
                </div><!--end row-->
            @endif

            <!-- Workshop Center -->
            @if (!$productWorkshop->isEmpty())
                <div class="row wow animate__animated animate__fadeInUp" data-wow-delay=".1s" style="margin-top: 10vh;">
                    <div class="col-lg-4 col-md-6 col-12 d-none d-sm-block">
                        <img width="500" src="{{ url('resources/images/model-9.png') }}"
                            class="rounded mx-auto d-block" alt="Banner Model 1 {{ config('app.name') }}"
                            title="Model Banner {{ config('app.name') }}" loading="eager" />
                    </div>
                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="d-flex justify-content-between ">
                            <h3>
                                Workshop Online
                            </h3>
                            <a href="{{ route('mainweb.product-sertikom', ['category' => 'workshop']) }}"
                                class="btn btn-md btn-soft-primary btn-pills">
                                Lihat Semua
                                <i class="uil uil-angle-right"></i>
                            </a>
                        </div>
                        <div class="row">

                            @foreach ($productWorkshop as $workshopItem)
                                @php
                                    $features = [];

                                    $url = route('mainweb.product-sertikom.training-seminar.show', [
                                        'feature' => \App\Enums\FeatureEnum::WORKSHOP->value,
                                        'id' => $workshopItem->id,
                                    ]);
                                    $visitorWorkshop = \App\Models\Sertikom\VisitorProdukModel::where(
                                        'ref_produk_id',
                                        $workshopItem->id,
                                    )->count();
                                    $image = asset('storage/' . $workshopItem->thumbnail);
                                @endphp
                                <div class="col-lg-6 col-md-6 col-12 mt-4 pt-2">
                                    <div class="card blog blog-primary rounded border-0 shadow overflow-hidden" itemscope
                                        itemtype="https://schema.org/Product"itemscope
                                        itemtype="https://schema.org/Product">
                                        <div class="position-relative">
                                            <img itemprop="image" src="{{ $image }}" class="card-img-top"
                                                alt="Thumbnail {{ $image }}"
                                                title="Thumbnail {{ $image }}" loading="eager">
                                            <div class="overlay"></div>
                                            <div class="teacher d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="mb-0">
                                                        <a href="javascript:void(0)"
                                                            class="user">{{ $workshopItem->instruktur }}</a>
                                                    </h6>
                                                    <p class="small my-0 profession">Instruktur</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="position-relative">
                                            <div class="shape overflow-hidden text-color-white">
                                                <svg viewBox="0 0 2880 48" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z"
                                                        fill="currentColor">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="card-body content">
                                            <span class="badge bg-primary mb-2">
                                                <i class="mdi mdi-calendar-range"></i>
                                                {{ \Carbon\Carbon::parse($workshopItem->tanggal_mulai)->translatedFormat('F') }}
                                            </span>
                                            <h6>
                                                <a href="#" class="text-primary">{{ $workshopItem->topik }}</a>
                                            </h6>
                                            <a itemprop="url" href="{{ $url }}" class="title text-dark h5">
                                                <span itemprop="name">{{ $workshopItem->produk }}</span>
                                            </a>
                                            <p class="text-muted mt-2" style="text-align: justify">
                                                <span class="d-flex fs-5 fw-bold text-primary" itemscope itemprop="offers"
                                                    itemtype="https://schema.org/Offer">
                                                    <meta itemprop="availability"
                                                        content="https://schema.org/OnlineOnly" />
                                                    <span itemprop="priceCurrency" content="IDR">Rp.</span>
                                                    <span itemprop="price" content="{{ $workshopItem->harga }}">
                                                        {{ Number::Format($workshopItem->harga) }}
                                                    </span>
                                                </span>
                                            </p>
                                            <ul
                                                class="list-unstyled d-flex justify-content-between border-top mt-3 pt-3 mb-0">
                                                <li class="text-muted small"><i class="uil uil-book-open text-info"></i>
                                                    Online
                                                </li>
                                                <li class="text-muted small ms-3"><i class="uil uil-eye text-primary"></i>
                                                    {{ $visitorWorkshop }}
                                                </li>
                                            </ul>

                                            <meta itemprop="description" content="{{ $workshopItem->deskripsi }}" />

                                            {{--  IDEA: get testimoni data from user testimoni, currently set to manual  --}}
                                            <div itemscope itemprop="aggregateRating"
                                                itemtype="https://schema.org/AggregateRating">
                                                <meta itemprop="ratingValue" content="5" />
                                                <meta itemprop="reviewCount"
                                                    content="{{ substr(strval($workshopItem->id), -2) + substr(strval($workshopItem->id), 0, 2) }}" />
                                            </div>
                                            <div itemscope itemprop="review" itemtype="https://schema.org/Review">
                                                <div itemscope itemprop="author" itemtype="https://schema.org/Person">
                                                    <meta itemprop="name" content="Qori Chairawan" />
                                                </div>
                                                <meta itemprop="datePublished"
                                                    content="{{ \Carbon\Carbon::parse($workshopItem->created_at)->addDays(2)->format('Y-m-d') }}" />
                                                <div itemscope itemprop="reviewRating"
                                                    itemtype="https://schema.org/Rating">
                                                    <meta itemprop="worstRating" content="4" />
                                                    <meta itemprop="ratingValue" content="5" />
                                                    <meta itemprop="bestRating" content="5" />
                                                </div>
                                                <meta itemprop="reviewBody" content="Workshop berbasis online terbaik." />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div><!--end row-->
            @endif

        </div><!--end container-->
    </section>
    <!-- End Training and Certification , Workshop-->

    <!-- Feature Start -->
    <section class="section">
        <!-- Product Start -->
        @if (count($productCategories) > 0)
            <div class="container pb-5" id="produk">
                <div class="row justify-content-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <div class="col-12 text-center">
                        <div class="section-title mb-4 pb-2">
                            <h2 class="title mb-4">Paket Simulasi Tryout Ujian CAT</h2>
                            <p class="text-muted mb-0 mx-auto">
                                Temukan paket simulasi ujian tryout yang sempurna untuk Kamu ! Dengan berbagai pilihan paket
                                yang
                                dirancang sesuai kebutuhan,
                                <span class="text-primary fw-bold">{{ $web->nama_bisnis }}</span> memberikan
                                solusi terbaik untuk persiapan ujian Kamu .
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
                                    <p class="text-muted">Fitur yang Kamu dapatkan dalam paket ini</p>

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
                                                Lihat Sekarang <i class="mdi mdi-arrow-right"></i>
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
@section('scripts')
    @php
        $breadcrumbItemListPosition = 0;
        $breadcrumbItemList = [
            [
                '@type' => 'ListItem',
                'position' => ++$breadcrumbItemListPosition,
                'name' => 'Home',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
