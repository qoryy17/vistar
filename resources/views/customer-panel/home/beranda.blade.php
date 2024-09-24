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

                <!-- Row -->
                <div class="row sidemenu-height">
                    <div class="col-lg-8">

                        <div class="alert alert-warning" role="alert">
                            <marquee> <strong>Informasi !</strong> Dapatkan akses menyeluruh untuk paket tryout CPNS, PPPK,
                                dan Kedinasan dengan hanya sekali beli !
                            </marquee>
                        </div>

                        @if ($testimoni->count() > 0)
                            {{-- Testimoni --}}
                            <div class="card custom-card d-none d-sm-block">
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-12">
                                            <div class="carousel slide" data-bs-ride="carousel" id="slideTestimoni">

                                                <div class="carousel-inner bg-dark">
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($testimoni as $slideTestimoni)
                                                        <div class="carousel-item {{ $no === 1 ? 'active' : '' }}">
                                                            <img class="d-block w-100 op-3"
                                                                src="{{ url('resources/images/bg-img.jpg') }}"
                                                                alt="Background Testimoni" title="Background Testimoni"
                                                                loading="eager" />
                                                            <div class="carousel-caption">
                                                                <p>{{ $slideTestimoni->nama_tryout }}</p>
                                                                <p class="tx-14 mb-0">
                                                                    {{ $slideTestimoni->testimoni }}
                                                                </p>
                                                                @for ($i = 0; $i < $slideTestimoni->rating; $i++)
                                                                    <i class="fa fa-star"
                                                                        style="color: rgb(255, 207, 16);"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        @php
                                                            $no++;
                                                        @endphp
                                                    @endforeach
                                                </div>

                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#slideTestimoni" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#slideTestimoni" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('mainweb.product') }}">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div class="row row-sm">
                                                <div class="col-12">
                                                    <div class="card-item-title">
                                                        <h2 class="fs-4 main-content-label tx-13 font-weight-bold mb-2">
                                                            Paket Tryout
                                                        </h2>
                                                        <span class="d-block tx-12 mb-0 text-muted">
                                                            Tersedia Untuk CPNS, PPPK, Kedinasan
                                                        </span>
                                                    </div>
                                                    <h3 class="mb-0 tx-24 mt-2" style="color: #0075B8;">
                                                        Mulai dari Rp. {{ number_format(49000, 0) }}
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('mainweb.index') }}">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div class="row row-sm">
                                                <div class="col-12">
                                                    <div class="card-item-title">
                                                        <h2 class="fs-4 main-content-label tx-13 font-weight-bold mb-2">
                                                            Tryout Gratis
                                                        </h2>
                                                        <span class="d-block tx-12 mb-0 text-muted">
                                                            Tersedia Untuk CPNS, PPPK, Kedinasan
                                                        </span>
                                                    </div>
                                                    <h3 style="text-decoration: line-through;" class="mb-0 tx-24 mt-2"
                                                        style="color: #0075B8;">
                                                        Rp. 0
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        {{-- Pembelian --}}
                        <div class="card custom-card" style="background-color: #F8AA3B">
                            <div class="card-body">
                                <div>
                                    <h2 class="fs-6 text-white">Pembelian Anda</h2>
                                    <h3 style="padding: 0px; margin:0px;" class="mb-2 fs-30 me-2">
                                        Total : {{ $countPembelian }} Pembelian
                                    </h3>
                                    <span class="text-white tx-14">Klik tombol dibawah untuk melihat pembelian</span>
                                    <a href="{{ route('site.pembelian') }}"
                                        class="btn btn-block btn-default btn-white mt-2"><i class="fa fa-list"></i>
                                        Lihat Semua</a>
                                </div>
                            </div>
                        </div>
                        {{-- Notif Event --}}
                        @if ($tryoutTerbaru)
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div>
                                        <h2 class="fs-6">Tryout Terbaru</h2>
                                        <h3 style="padding: 0px; margin:0px;color: #F8AA3B;" class="mb-2 fs-20 me-2">
                                            {{ $tryoutTerbaru->nama_tryout }}
                                        </h3>
                                        <span class="text-muted tx-14">{{ $tryoutTerbaru->keterangan }}</span>
                                        <a href="{{ route('mainweb.product') }}"
                                            class="btn btn-block btn-default mt-2 btn-web1"><i
                                                class="fa fa-shopping-cart"></i>
                                            Beli Sekarang</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>
@endsection
