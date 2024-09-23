@extends('mitra.layout.index')
@section('title', $titlePage)
@section('content')
    <div class="main-content pt-0 hor-content">
        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="">
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $titlePage }}</h2>
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}"
                                    {{ $breadcrumb['active'] ? 'aria-current="page"' : '' }}>
                                    <a title="{{ $breadcrumb['title'] }}" href="{{ $breadcrumb['url'] }}">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- Row -->
                <div class="row sidemenu-height flex-column-reverse flex-lg-row">
                    <div class="col-lg-8">
                        <div class="alert alert-warning" role="alert">
                            <marquee>
                                <strong>Informasi !</strong> Bagikan Kode Promo anda untuk mendapatkan keuntungan dari
                                setiap Transaksi
                            </marquee>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-order">
                                            <span class="main-content-label mb-3 pt-1">Total Pendapatan</span>
                                            <h2 class="text-end">
                                                <i class="text-primary mdi mdi-poll-box icon-size float-start"></i>
                                                <span class="font-weight-bold">
                                                    Rp. {{ number_format($statistics['total_income']) }}
                                                </span>
                                            </h2>
                                            <p class="mb-0 mt-4 text-muted">
                                                Total Pendapatan Hari Ini
                                                <span class="float-end">
                                                    Rp. {{ number_format($statistics['total_income_today']) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-order ">
                                            <span class="main-content-label mb-3 pt-1">
                                                Total Pembelian Dengan Kode Promo
                                            </span>
                                            <h2 class="text-end card-item-icon card-icon">
                                                <i class="text-primary mdi mdi-cart icon-size float-start"></i>
                                                <span class="font-weight-bold">
                                                    {{ $statistics['total_transaction'] }}
                                                </span>
                                            </h2>
                                            <p class="mb-0 mt-4 text-muted">
                                                Hari Ini
                                                <span class="float-end">{{ $statistics['total_transaction_today'] }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card custom-card" style="background-color: #F8AA3B">
                            <div class="card-body">
                                <div>
                                    <h5 class="text-white">Balance</h5>
                                    <h6 style="padding: 0px; margin:0px;" class="mb-2">
                                        <span class="fs-30 me-2">Rp. {{ $userMitra->balances }}</span>
                                    </h6>
                                    <span class="text-white tx-14">Klik tombol dibawah untuk melihat Transaksi</span>
                                    <a href="{{ route('mitra.transactions.index') }}"
                                        class="btn btn-block btn-default btn-white mt-2">
                                        <i class="fa fa-list"></i>
                                        Lihat Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card">
                            <div class="card-body">
                                @php
                                    $shareUrl = route('promo-code.apply', [
                                        'type' => 'mitra',
                                        'promoCode' => $userMitra->promotion_code,
                                    ]);
                                @endphp
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <span class="fs-4 me-2">{{ $userMitra->promotion_code }}</span>
                                    <input id="promotion_code" value="{{ $shareUrl }}" style="display: none;" />
                                    <span
                                        onclick="copyToClipboard('promotion_code');swal({title: 'URL bagikan Kode Promo berhasil dicopy', type: 'success'})"
                                        title="Copy URL Bagikan Kode Promo" class="fa fa-clipboard" style="cursor: copy;">
                                    </span>
                                </div>
                                <span class="tx-14 text-muted">Bagikan: </span>
                                <div class="d-flex gap-2">
                                    <a title="Bagikan Kode Promo ke Facebook"
                                        href="https://web.facebook.com/share_channel/?link={{ $shareUrl }}&source_surface=external_reshare&display&hashtag"
                                        target="_blank" class="share-it share-fb">
                                        <i class="mdi mdi-facebook"></i>
                                        <span class="fw-bold">Facebook</span>
                                    </a>
                                    <a title="Bagikan Kode Promo ke Whatsapp"
                                        href="https://api.whatsapp.com/send/?text={{ urlencode('Dapatkan Potongan harga menggunakan Kode Promo `' . $userMitra->promotion_code . '` disini ' . $shareUrl) }}&type=custom_url&app_absent=0"
                                        target="_blank" class="share-it share-wa">
                                        <i class="mdi mdi-whatsapp"></i>
                                        <span class="fw-bold">Whatsapp</span>
                                    </a>
                                    <a title="Bagikan Kode Promo ke Twitter/X"
                                        href="https://x.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode('Dapatkan Potongan harga menggunakan Kode Promo `' . $userMitra->promotion_code . '`') }}"
                                        target="_blank" class="share-it share-tw">
                                        <i class="mdi mdi-twitter"></i>
                                        <span class="fw-bold">X</span>
                                    </a>
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
