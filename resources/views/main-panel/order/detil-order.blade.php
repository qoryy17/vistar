@extends('main-panel.layout.main')
@section('title', $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">
        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $page_title }}</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">{{ $bc1 }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $bc2 }}</li>
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
                    <div class="col-lg-12">

                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <h4>
                                                    Detail Pemesanan/Order
                                                </h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="500px">Order ID</td>
                                            <td>{{ $detilOrder->id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Faktur ID</td>
                                            <td>{{ $detilOrder->faktur_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama</td>
                                            <td>{{ $detilOrder->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment ID</td>
                                            <td>{{ $detilOrder->payment_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Referensi Order Produk Tryout</td>
                                            <td>{{ $detilOrder->ref_order_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Produk Tryout</td>
                                            <td>{{ $detilOrder->nama_tryout }}</td>
                                        </tr>
                                        <tr>
                                            <td>Harga Pembelian</td>
                                            <td>{{ Number::currency($detilOrder->nominal, in: 'IDR') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Transaksi</td>
                                            <td>{{ $detilOrder->waktu_transaksi }}</td>
                                        </tr>
                                        <tr>
                                            <td>Metode Pembayaran</td>
                                            <td>{{ $detilOrder->metode }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                @if ($detilOrder->status_transaksi == 'pending')
                                                    <strong class="text-warning">
                                                        {{ $detilOrder->status_transaksi }}</strong>
                                                @elseif ($detilOrder->status_transaksi == 'paid')
                                                    <strong class="text-success">
                                                        {{ $detilOrder->status_transaksi }}</strong>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td>JSON</td>
                                            <td>
                                                {{ $detilOrder->metadata }}
                                            </td>
                                        </tr> --}}
                                    </table>
                                </div>
                                <a href="{{ route('listOrders.main') }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-reply"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>
@endsection
