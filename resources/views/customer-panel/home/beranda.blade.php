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
                            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Informasi !</strong> Dapatkan akses menyeluruh untuk paket tryout CPNS, PPPK, dan
                            Kedinasan dengan hanya sekali beli !
                        </div>

                        {{-- Testimoni --}}
                        <div class="card custom-card d-none d-sm-block">
                            <div class="card-body">
                                <div class="row row-sm">
                                    <div class="col-12">
                                        <div class="carousel slide" data-bs-ride="carousel" id="slideTestimoni">
                                            <ol class="carousel-indicators">
                                                <li class="active" data-bs-slide-to="0" data-bs-target="#slideTestimoni">
                                                </li>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($testimoni->get() as $slideTestimoni)
                                                    <li data-bs-slide-to="{{ $no }}"
                                                        data-bs-target="#slideTestimoni"></li>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endforeach
                                            </ol>
                                            <div class="carousel-inner bg-dark">
                                                <div class="carousel-item active">
                                                    <img alt="img" class="d-block w-100 op-3"
                                                        src="{{ url('resources/bg-img.jpg') }}">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h5>{{ $testimoni->first()->nama_lengkap }}</h5>
                                                        <p class="tx-14">
                                                            {{ $testimoni->first()->testimoni }}
                                                        </p>
                                                        @for ($i = 0; $i < $testimoni->first()->rating; $i++)
                                                            <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
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
                                                                <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-order ">
                                            <label class="main-content-label mb-3 pt-1">Total Pengguna</label>
                                            <h2 class="text-end card-item-icon card-icon">
                                                <i style="color: #0075B8;"
                                                    class="mdi mdi-account-multiple icon-size float-start"></i>
                                                <span class="font-weight-bold">{{ $countCustomer }}</span>
                                            </h2>
                                            <p class="mb-0 mt-4 text-muted">Pengguna Hari Ini<span
                                                    class="float-end">{{ $countCustomerPerhari }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-order">
                                            <label class="main-content-label mb-3 pt-1">Tryout Terjual</label>
                                            <h2 class="text-end"><i style="color: #0075B8;"
                                                    class="icon-size mdi mdi-poll-box   float-start"></i>
                                                <span class="font-weight-bold">{{ $countTryout }}</span>
                                            </h2>
                                            <p class="mb-0 mt-4 text-muted">Tryout Terjual Hari Ini<span
                                                    class="float-end">{{ $countTryoutPerhari }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
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
                                            <p class="mb-0 tx-24 mt-2"><b style="color: #0075B8;">Rp. 100.000</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
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
                                            <p class="mb-0 tx-24 mt-2"><b style="color: #0075B8;">Rp. 0</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        {{-- Notif Event --}}
                        <div class="card custom-card">
                            <div class="card-body">
                                <div>
                                    <h5>Informasi Terbaru</h5>
                                    <h6 style="padding: 0px; margin:0px;color: #F8AA3B;" class="mb-2">
                                        <span class="fs-20 me-2">{{ $tryoutTerbaru->nama_tryout }}</span>
                                    </h6>
                                    <span class="text-muted tx-14">{{ $tryoutTerbaru->keterangan }}</span>
                                    <a href="{{ route('mainweb.produk-berbayar') }}"
                                        class="btn btn-block btn-default mt-2 btn-web1"><i
                                            class="fa fa-shopping-cart"></i>
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
