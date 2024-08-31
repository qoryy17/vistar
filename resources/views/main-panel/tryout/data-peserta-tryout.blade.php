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
                                <form action="{{ route('tryouts.peserta-tryout') }}" method="GET">
                                    @csrf
                                    @method('GET')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="Kategori">Kategori</label>
                                                <select name="kategori" class="form-control" id="Kategori">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    <option value="CPNS">CPNS</option>
                                                    <option value="PPK">PPPK</option>
                                                    <option value="Kedinasan">Kedinasan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="JenisTryout">Jenis Tryout</label>
                                                <select name="jenisTryout" class="form-control" id="JenisTryout">
                                                    <option value="">-- Pilih Jenis Tryout --</option>
                                                    <option value="Berbayar">Berbayar</option>
                                                    <option value="Gratis">Gratis</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Tahun">Tahun </label>
                                                <select name="tahun" id="Tahun" class="form-control selectTahun">
                                                    <option value="">-- Pilih Tahun --</option>
                                                    @for ($tahun = 2024; $tahun <= 2026; $tahun++)
                                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="Filter">Filter </label> <br>
                                                <button type="submit" class="btn btn-block btn-default btn-web"><i
                                                        class="fa fa-search"></i>
                                                    Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered border-bottom">
                                        <thead>
                                            <tr>
                                                <td width="1%">No</td>
                                                <td>Informasi Peserta</td>
                                                <th>Kalkulasi Nilai</th>
                                                <th>Waktu Tercatat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pesertaTryout as $index => $peserta)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>Produk :</strong> {{ $peserta->nama_tryout }} <br>
                                                        <strong>Peserta :</strong> {{ $peserta->nama_lengkap }}
                                                    </td>
                                                    <td>
                                                        Soal Terjawab : {{ $peserta->terjawab }} <br>
                                                        Soal Tidak Terjawab : {{ $peserta->tidak_terjawab }} <br>
                                                        Soal Benar : {{ $peserta->benar }} <br>
                                                        Soal Salah : {{ $peserta->salah }} <br>
                                                        Total Nilai :
                                                        <strong>{{ Number::format($peserta->skd, 3) }}</strong>

                                                    </td>
                                                    <td>{{ $peserta->waktu_mulai }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $pesertaTryout->links() }}
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
