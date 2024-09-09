@extends('customer-panel.layout.main')
@section('title', 'Selamat ' . $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">
        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">Selamat {{ $page_title }}</h2>
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
                        @php
                            $no = 1;
                            $latestTestimoni = $testimoni->first();
                        @endphp
                        @if ($latestTestimoni)
                            {{-- Testimoni --}}
                            <div class="card custom-card d-none d-sm-block">
                                <div class="card-body">
                                    <div class="row row-sm">
                                        <div class="col-12">
                                            <div class="carousel slide" data-bs-ride="carousel" id="slideTestimoni">

                                                <ol class="carousel-indicators">
                                                    @if ($latestTestimoni)
                                                        <li class="active" data-bs-slide-to="0"
                                                            data-bs-target="#slideTestimoni">
                                                        </li>
                                                        @foreach ($testimoni->get() as $slideTestimoni)
                                                            <li data-bs-slide-to="{{ $no }}"
                                                                data-bs-target="#slideTestimoni"></li>
                                                            @php
                                                                $no++;
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                </ol>
                                                <div class="carousel-inner bg-dark">
                                                    @if ($latestTestimoni)
                                                        <div class="carousel-item active">
                                                            <img alt="img" class="d-block w-100 op-3"
                                                                src="{{ url('resources/bg-img.jpg') }}">
                                                            <div class="carousel-caption d-none d-md-block">
                                                                <h5>{{ $latestTestimoni->nama_lengkap }}</h5>
                                                                <p class="tx-14">
                                                                    {{ $latestTestimoni->testimoni }}
                                                                </p>
                                                                @for ($i = 0; $i < $latestTestimoni->rating; $i++)
                                                                    <i class="fa fa-star"
                                                                        style="color: rgb(255, 207, 16);"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        @foreach ($testimoni->get() as $slideTestimoni)
                                                            <div class="carousel-item">
                                                                <img alt="img" class="d-block w-100 op-3"
                                                                    src="{{ url('resources/bg-img.jpg') }}">
                                                                <div class="carousel-caption d-none d-md-block">
                                                                    <h5>{{ $slideTestimoni->nama_lengkap }}</h5>
                                                                    <p class="tx-14">{{ $slideTestimoni->testimoni }}
                                                                    </p>
                                                                    @for ($i = 0; $i < $slideTestimoni->rating; $i++)
                                                                        <i class="fa fa-star"
                                                                            style="color: rgb(255, 207, 16);"></i>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('mainweb.produk-berbayar') }}">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div class="row row-sm">
                                                <div class="col-12">
                                                    <div class="card-item-title">
                                                        <label class="main-content-label tx-13 font-weight-bold mb-2">
                                                            <h4 style="padding: 0px; margin:0px;">Paket Tryout</h4>
                                                        </label>
                                                        <span class="d-block tx-12 mb-0 text-muted">Tersedia Untuk CPNS,
                                                            PPPK, Kedinasan</span>
                                                    </div>
                                                    <p class="mb-0 tx-24 mt-2"><b style="color: #0075B8;">Mulai dari
                                                            {{ Number::currency(90000, in: 'IDR') }}</b>
                                                    </p>
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
                                                        <label class="main-content-label tx-13 font-weight-bold mb-2">
                                                            <h4 style="padding: 0px; margin:0px;">Tryout Gratis</h4>
                                                        </label>
                                                        <span class="d-block tx-12 mb-0 text-muted">Tersedia Untuk CPNS,
                                                            PPPK, Kedinasan</span>
                                                    </div>
                                                    <p style="text-decoration: line-through;" class="mb-0 tx-24 mt-2"><b
                                                            style="color: #0075B8;">Rp. 0</b>
                                                    </p>
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
                                    <h5 class="text-white">Pembelian Anda</h5>
                                    <h6 style="padding: 0px; margin:0px;" class="mb-2">
                                        <span class="fs-30 me-2">Total : {{ $countPembelian }} Pembelian</span>
                                    </h6>
                                    <span class="text-white tx-14">Klik tombol dibawah untuk melihat pembelian</span>
                                    <a href="{{ route('site.pembelian') }}"
                                        class="btn btn-block btn-default btn-white mt-2"><i class="fa fa-list"></i>
                                        Lihat Semua</a>
                                </div>
                            </div>
                        </div>
                        {{-- Notif Event --}}
                        <div class="card custom-card">
                            <div class="card-body">
                                <div>
                                    <h5>Tryout Terbaru</h5>
                                    <h6 style="padding: 0px; margin:0px;color: #F8AA3B;" class="mb-2">
                                        <span class="fs-20 me-2">{{ $tryoutTerbaru->nama_tryout }}</span>
                                    </h6>
                                    <span class="text-muted tx-14">{{ $tryoutTerbaru->keterangan }}</span>
                                    <a href="{{ route('mainweb.produk-berbayar') }}"
                                        class="btn btn-block btn-default mt-2 btn-web1"><i class="fa fa-shopping-cart"></i>
                                        Beli Sekarang</a>
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
