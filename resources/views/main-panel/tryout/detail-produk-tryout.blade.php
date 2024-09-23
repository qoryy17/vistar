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
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/tryout/' . $tryout->thumbnail) }}" class="img-fluid"
                                            alt="Thubmnail {{ $tryout->nama_tryout }}"
                                            title="Thubmnail {{ $tryout->nama_tryout }}" loading="lazy" />
                                    </div>
                                    <div class="col-md-8">
                                        <table class="table">
                                            <tr>
                                                <td>Nama Produk</td>
                                                <th id="name">
                                                    <strong>{{ $tryout->nama_tryout }}</strong>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Keterangan
                                                </td>
                                                <td>
                                                    {{ $tryout->keterangan }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Kategori
                                                </td>
                                                <td>
                                                    {{ $kategori->judul }} - {{ $kategori->status }} -
                                                    {{ $kategori->aktif }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Status
                                                </td>
                                                <td>
                                                    {{ $tryout->status }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Harga / Harga Promo
                                                </td>
                                                <td>
                                                    {{ Number::currency($pengaturan->harga, in: 'IDR') }} / Promo :
                                                    {{ $pengaturan->harga_promo != null ? Number::currency($pengaturan->harga_promo, in: 'IDR') : 'IDR ' . 0 }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Durasi Ujian
                                                </td>
                                                <td>
                                                    {{ $pengaturan->durasi }} Menit
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Jumlah Soal
                                                </td>
                                                <td>
                                                    {{ $totalSoal }} Soal
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Masa Aktif
                                                </td>
                                                <td>
                                                    {{ $pengaturan->masa_aktif }} Hari
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Passing Grade SKD
                                                </td>
                                                <td>
                                                    {{ $pengaturan->passing_grade }} Poin
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4">
                                    @foreach ($klasifikasi as $klasifikasiSoal)
                                        <div class="col">
                                            {{ $klasifikasiSoal->judul }} ({{ $klasifikasiSoal->alias }}) :
                                            {{ $klasifikasiSoal->passing_grade }} Poin
                                        </div>
                                    @endforeach
                                </div>
                                <a href="{{ route('tryouts.index') }}" class="btn btn-sm btn-warning">
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
