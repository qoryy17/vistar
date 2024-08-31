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
                                <div class="row m-1">
                                    <div class="col-md-6">
                                        <h4 class="text-primary">
                                            Customer : {{ $customer }}
                                        </h4>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-warning">
                                            Kode Referral : {{ $kodeReferral }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td width="2%">No</td>
                                            <td>Pengguna Referral</td>
                                            <td>Produk Tryout</td>
                                            <td>Waktu Tercatat</td>
                                        </tr>
                                        @if ($referral)
                                            <tr class="text-center">
                                                <td colspan="4">Belum ada referral yang digunakan</td>
                                            </tr>
                                        @endif
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($referral as $row)
                                            <tr>
                                                <td></td>
                                                <td>{{ $row->nama_lengkap }}</td>
                                                <td>{{ $row->nama_tryout }}</td>
                                                <td>{{ $row->created_at }}</td>
                                            </tr>
                                            @php
                                                $no++;
                                            @endphp
                                        @endforeach
                                    </table>
                                </div>
                                <a href="{{ route('referral.main') }}" class="btn btn-sm btn-warning">
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
