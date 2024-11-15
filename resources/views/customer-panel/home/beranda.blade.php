@extends('customer-panel.layout.main')
@section('title', 'Selamat ' . $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">
        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h1 class="main-content-title tx-24 mg-b-5">Selamat {{ $page_title }}</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">

                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                <div class="alert alert-primary" role="alert">
                    <marquee> <strong>Informasi !</strong>
                        Upgrade skill kamu dengan mengikuti pelatihan pada platform kami dapatkan pembelajaran yang
                        menarik dan interaktif atau kamu bisa ikuti seminar ataupun workshop untuk menambah wawasan dan
                        relasi kamu > Dapatkan akses menyeluruh untuk paket simulasi tryout CPNS, PPPK, dan
                        Kedinasan dengan hanya sekali beli !
                    </marquee>
                </div>
                <!-- Row -->
                <div class="row sidemenu-height">
                    <div class="col-lg-9">
                        <!-- Notifikasi Produk Terbaru -->
                        @if ($trainingTerbaru)
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div id="lightgallery">
                                                <div data-responsive="{{ asset('storage/' . $trainingTerbaru->thumbnail) }}"
                                                    data-src="{{ asset('storage/' . $trainingTerbaru->thumbnail) }}"
                                                    data-sub-html="<h4>{{ $trainingTerbaru->produk }}</h4><p>{{ $trainingTerbaru->topik }}</p>">
                                                    <a href="" class="wd-100p">
                                                        <img class="img-fluid"
                                                            src="{{ asset('storage/' . $trainingTerbaru->thumbnail) }}"
                                                            alt="Thumbnail {{ $trainingTerbaru->produk }}"
                                                            title="Thumbnail {{ $trainingTerbaru->produk }}"
                                                            loading="eager" />
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <h2 class="fs-5 mt-2 text-primary">Pelatihan Terbaru</h2>
                                            <h3 style="padding: 0px; margin:0px;" class="mb-2 fs-20 me-2">
                                                {{ $trainingTerbaru->produk }}
                                            </h3>
                                            <p>
                                                <span class="badge bg-primary mb-2">
                                                    <i class="fa fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($trainingTerbaru->tanggal_mulai)->translatedFormat('F') }}
                                                </span>
                                                <br>
                                                Topik : <span class="text-primary">{{ $trainingTerbaru->topik }}</span>
                                            </p>
                                            @php
                                                $checkOrderTraining = App\Models\Sertikom\OrderPelatihanSeminarModel::where(
                                                    'produk_pelatihan_seminar_id',
                                                    $trainingTerbaru->id,
                                                )
                                                    ->where('customer_id', Auth::user()->customer_id)
                                                    ->first();
                                            @endphp
                                            @if (!$checkOrderTraining)
                                                <a href="{{ route('mainweb.product-sertikom', ['category' => 'pelatihan']) }}"
                                                    class="btn btn-primary btn-sm d-block d-md-inline-block">
                                                    Daftar Sekarang
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('customer.detail-sertikom', ['category' => 'pelatihan', 'id' => Crypt::encrypt($checkOrderTraining->id)]) }}"
                                                    class="btn btn-primary btn-sm d-block d-md-inline-block">
                                                    Lihat Pelatihan
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @endif
                                            <h6 class="mt-3">Jadwal Pelatihan</h6>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $trainingTerbaru->tanggal_mulai)->format('d/m/Y') }}
                                            sampai
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $trainingTerbaru->tanggal_selesai)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($seminarTerbaru)
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div id="lightgallery1">
                                                <div data-responsive="{{ asset('storage/' . $seminarTerbaru->thumbnail) }}"
                                                    data-src="{{ asset('storage/' . $seminarTerbaru->thumbnail) }}"
                                                    data-sub-html="<h4>{{ $seminarTerbaru->produk }}</h4><p>{{ $seminarTerbaru->topik }}</p>">
                                                    <a href="" class="wd-100p">
                                                        <img class="img-fluid"
                                                            src="{{ asset('storage/' . $seminarTerbaru->thumbnail) }}"
                                                            alt="Thumbnail {{ $seminarTerbaru->produk }}"
                                                            title="Thumbnail {{ $seminarTerbaru->produk }}"
                                                            loading="eager" />
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <h2 class="fs-5 mt-2 text-primary">Seminar Terbaru</h2>
                                            <h3 style="padding: 0px; margin:0px;" class="mb-2 fs-20 me-2">
                                                {{ $seminarTerbaru->produk }}
                                            </h3>
                                            <p>
                                                <span class="badge bg-primary mb-2">
                                                    <i class="fa fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($seminarTerbaru->tanggal_mulai)->translatedFormat('F') }}
                                                </span>
                                                <br>
                                                Topik : <span class="text-primary">{{ $seminarTerbaru->topik }}</span>
                                            </p>
                                            @php
                                                $checkOrderSeminar = App\Models\Sertikom\OrderPelatihanSeminarModel::where(
                                                    'produk_pelatihan_seminar_id',
                                                    $seminarTerbaru->id,
                                                )
                                                    ->where('customer_id', Auth::user()->customer_id)
                                                    ->first();
                                            @endphp
                                            @if (!$checkOrderSeminar)
                                                <a href="{{ route('mainweb.product-sertikom', ['category' => 'seminar']) }}"
                                                    class="btn btn-primary btn-sm d-block d-md-inline-block">
                                                    Daftar Sekarang
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('customer.detail-sertikom', ['category' => 'seminar', 'id' => Crypt::encrypt($checkOrderSeminar->id)]) }}"
                                                    class="btn btn-primary btn-sm d-block d-md-inline-block">
                                                    Lihat Seminar
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @endif
                                            <h6 class="mt-3">Jadwal Workshop</h6>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $seminarTerbaru->tanggal_mulai)->format('d/m/Y') }}
                                            sampai
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $seminarTerbaru->tanggal_selesai)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($workshopTerbaru)
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div id="lightgallery2">
                                                <div data-responsive="{{ asset('storage/' . $workshopTerbaru->thumbnail) }}"
                                                    data-src="{{ asset('storage/' . $workshopTerbaru->thumbnail) }}"
                                                    data-sub-html="<h4>{{ $workshopTerbaru->produk }}</h4><p>{{ $workshopTerbaru->topik }}</p>">
                                                    <a href="" class="wd-100p">
                                                        <img class="img-fluid"
                                                            src="{{ asset('storage/' . $workshopTerbaru->thumbnail) }}"
                                                            alt="Thumbnail {{ $workshopTerbaru->produk }}"
                                                            title="Thumbnail {{ $workshopTerbaru->produk }}"
                                                            loading="eager" />
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <h2 class="fs-5 mt-2 text-primary">Workshop Terbaru</h2>
                                            <h3 style="padding: 0px; margin:0px;" class="mb-2 fs-20 me-2">
                                                {{ $workshopTerbaru->produk }}
                                            </h3>
                                            <p>
                                                <span class="badge bg-primary mb-2">
                                                    <i class="fa fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($workshopTerbaru->tanggal_mulai)->translatedFormat('F') }}
                                                </span>
                                                <br>
                                                Topik : <span class="text-primary">{{ $workshopTerbaru->topik }}</span>
                                            </p>
                                            @php
                                                $checkOrderWorkshop = App\Models\Sertikom\OrderPelatihanSeminarModel::where(
                                                    'produk_pelatihan_seminar_id',
                                                    $workshopTerbaru->id,
                                                )
                                                    ->where('customer_id', Auth::user()->customer_id)
                                                    ->first();
                                            @endphp
                                            @if (!$checkOrderWorkshop)
                                                <a href="{{ route('mainweb.product-sertikom', ['category' => 'workshop']) }}"
                                                    class="btn btn-primary btn-sm d-block d-md-inline-block">
                                                    Daftar Sekarang
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('customer.detail-sertikom', ['category' => 'workshop', 'id' => Crypt::encrypt($checkOrderWorkshop->id)]) }}"
                                                    class="btn btn-primary btn-sm d-block d-md-inline-block">
                                                    Lihat Workshop
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            @endif
                                            <h6 class="mt-3">Jadwal Workshop</h6>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $workshopTerbaru->tanggal_mulai)->format('d/m/Y') }}
                                            sampai
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $workshopTerbaru->tanggal_selesai)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($tryoutTerbaru)
                            <div class="card custom-card">
                                <div class="card-body">
                                    <h2 class="fs-6">Simulasi Tryout Terbaru</h2>
                                    <h3 style="padding: 0px; margin:0px;" class="mb-2 fs-20 me-2">
                                        {{ $tryoutTerbaru->nama_tryout }}
                                    </h3>
                                    <span class="text-muted tx-14">{{ $tryoutTerbaru->keterangan }}</span>
                                    <br>
                                    <a href="{{ route('mainweb.product') }}"
                                        class="btn btn-primary btn-sm mt-2 d-block d-md-inline-block">
                                        Beli Sekarang
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <!-- End Notifikasi Produk Terbaru -->
                    </div>
                    <div class="col-lg-3">
                        <!-- Pembelian Pelatihan  -->
                        <a href="{{ route('site.pembelian-sertikom', ['category' => 'pelatihan']) }}"
                            title="Pembelian Pelatihan Kamu">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="card-order">
                                        <label class="main-content-label mb-3 pt-1">Total Beli Pelatihan</label>
                                        <h2 class="text-end">
                                            <i class="mdi mdi-cart icon-size float-start text-primary"></i>
                                            <span class="font-weight-bold">{{ $countPelatihan }}</span>
                                        </h2>
                                        <p class="mb-0 mt-4 text-muted">Pembelian Pelatihan Kamu</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Pembelian Pelatihan -->

                        <!-- Pembelian Seminar  -->
                        <a href="{{ route('site.pembelian-sertikom', ['category' => 'seminar']) }}"
                            title="Pembelian Seminar Kamu">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="card-order">
                                        <label class="main-content-label mb-3 pt-1">Total Beli Seminar</label>
                                        <h2 class="text-end">
                                            <i class="mdi mdi-cart icon-size float-start text-primary"></i>
                                            <span class="font-weight-bold">{{ $countSeminar }}</span>
                                        </h2>
                                        <p class="mb-0 mt-4 text-muted">Pembelian Seminar Kamu</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Pembelian Seminar -->

                        <!-- Pembelian Workshop  -->
                        <a href="{{ route('site.pembelian-sertikom', ['category' => 'workshop']) }}"
                            title="Pembelian Workshop Kamu">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="card-order">
                                        <label class="main-content-label mb-3 pt-1">Total Beli Workshop</label>
                                        <h2 class="text-end">
                                            <i class="mdi mdi-cart icon-size float-start text-primary"></i>
                                            <span class="font-weight-bold">{{ $countWorkshop }}</span>
                                        </h2>
                                        <p class="mb-0 mt-4 text-muted">Pembelian Workshop Kamu</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Pembelian Workshop -->

                        <!-- Pembelian Simulasi Tryout  -->
                        <a href="{{ route('site.pembelian') }}" title="Pembelian Tryout Kamu">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="card-order">
                                        <label class="main-content-label mb-3 pt-1">Total Beli Simulasi Tryout</label>
                                        <h2 class="text-end">
                                            <i class="mdi mdi-cart icon-size float-start text-primary"></i>
                                            <span class="font-weight-bold">{{ $countTryout }}</span>
                                        </h2>
                                        <p class="mb-0 mt-4 text-muted">Pembelian Simulasi Tryout Kamu</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Pembelian Simulasi Tryout -->
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>
@endsection
