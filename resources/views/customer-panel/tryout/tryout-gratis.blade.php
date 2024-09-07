@extends('customer-panel.layout.main')
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
                            <li class="breadcrumb-item"><a href="#">Tryout</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">

                        </div>
                    </div>
                </div>

                @if ($ujianGratis->count() > 0)
                    <!-- End Page Header -->
                    <div class="row">
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($ujianGratis as $tryout)
                            @if (!$tryout->produk_tryout_id)
                                <div class="col-lg-4">
                                    {{-- Informasi Paket Tryout --}}
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a class="btn btn-primary"
                                                    href="{{ route('mainweb.daftar-tryout-gratis') }}">
                                                    Pilih Tryout Gratis
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    $cekExam = App\Models\Ujian::where('limit_tryout_id', $tryout->id)
                                        ->orderBy('created_at', 'DESC')
                                        ->first();
                                    $date = \Carbon\Carbon::parse($tryout->created_at);
                                    $newDate = $date->addDays($tryout->masa_aktif);
                                    $masaAktif = now()->diffInDays($newDate, false);

                                    $isFinish = $cekExam?->status_ujian == 'Selesai';
                                    $isWaiting = $cekExam?->status_ujian == 'Sedang Dikerjakan';
                                @endphp
                                <div class="col-lg-4">
                                    {{-- Informasi Paket Tryout --}}
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div>
                                                <h6>Informasi Paket</h6>
                                                <h6 style="padding: 0px; margin:0px;" class="mb-2">
                                                    <span class="fs-30 me-2" style="color: #0075B8;">
                                                        {{ $tryout->nama_tryout }}
                                                    </span>
                                                    <span class="badge bg-success mt-2">Gratis 1x</span>
                                                </h6>
                                                <div class="text-muted tx-12">
                                                    @if ($isFinish)
                                                        <span>
                                                            <i class="fa fa-info"></i>
                                                            Anda sudah mengerjakan Ujian
                                                        </span>
                                                    @else
                                                        @if ($masaAktif > 0)
                                                            <span>
                                                                Klik tombol dibawah untuk melanjutkan ujian Tryout berbasis
                                                                CBT/CAT
                                                            </span>
                                                            @if ($cekExam)
                                                                @if ($isWaiting)
                                                                    <button
                                                                        onclick='document.getElementById("mulaiUjian{{ $no }}").submit();'
                                                                        class="btn btn-block btn-default mt-2 btn-web1">
                                                                        <i class="fa fa-check-circle"></i> Lanjut
                                                                        Mengerjakan
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <button
                                                                    onclick='swal({
                                                        title: "Mulai Ujian",
                                                        text: "Apakah anda ingin memulai ujian sekarang ?",
                                                        type: "warning",
                                                        showCancelButton: true,
                                                        closeOnConfirm: false,
                                                        confirmButtonText: "Mulai",
                                                        cancelButtonText: "Batal",
                                                        showLoaderOnConfirm: true }, function ()
                                                            {
                                                            setTimeout(function(){
                                                                document.getElementById("mulaiUjian{{ $no }}").submit();
                                                        }, 1000); });'
                                                                    class="btn btn-block btn-default mt-2 btn-web">
                                                                    <i class="fa fa-check-circle"></i>
                                                                    Mulai Ujian
                                                                </button>
                                                            @endif

                                                            @if (floor(abs($masaAktif)) == 0)
                                                                <small class="text-danger">Masa Aktif Berakhir Dalam
                                                                    {{ $newDate->diffForHumans() }}</small>
                                                            @else
                                                                <small class="text-danger">Sisa Masa Aktif Paket :
                                                                    {{ ceil($masaAktif) }} Hari</small>
                                                            @endif

                                                            <form id="mulaiUjian{{ $no }}"
                                                                action="{{ route('ujian.main', ['id' => Crypt::encrypt($tryout->id), 'param' => Crypt::encrypt('gratis')]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('POST')
                                                            </form>
                                                        @else
                                                            <span class="text-muted tx-12">
                                                                Masa Aktif Paket Telah Berakhir :
                                                                {{ $newDate->diffForHumans() }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @php
                                $no++;
                            @endphp
                        @endforeach
                    </div>
                    <!-- Row -->
                    <div class="row sidemenu-height">
                        <div class="col-lg-12">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-bottom">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Informasi Ujian</th>
                                                    <th class="text-center">Total Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $page = $hasilUjian->currentPage();
                                                    $no = ($page - 1) * $hasilUjian->perPage() + 1;
                                                @endphp
                                                @foreach ($hasilUjian as $row)
                                                    @php
                                                        $examResult = $row->hasil;

                                                        $testimoni = $examResult?->testimoni;

                                                        $tryout = $row->limit?->tryout;

                                                        $tryoutName =
                                                            $tryout?->nama_tryout ?? 'Tryout ID: ' . $row->tryout?->id;
                                                    @endphp
                                                    <tr>
                                                        <td style="vertical-align: top;">{{ $no }}</td>
                                                        <td>
                                                            @if ($examResult && $tryout)
                                                                <a href="{{ route('ujian.hasil', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                    title="Klik untuk melihat detil">
                                                                    <h5 style="color: #0075B8;">
                                                                        {{ $tryoutName }} - {{ $row->id }}
                                                                    </h5>
                                                                </a>
                                                            @else
                                                                <h5 style="color: #0075B8;">
                                                                    {{ $tryoutName }} - {{ $row->id }}
                                                                </h5>
                                                            @endif

                                                            <table class="table">
                                                                <tr>
                                                                    <th id="table-head-start_time">Waktu Mulai</th>
                                                                    <td>
                                                                        {{ \Carbon\Carbon::parse($row->waktu_mulai)->format('d/m/Y H:i:s') }}
                                                                    </td>
                                                                    <th id="table-head-end_time">Selesai</th>
                                                                    <td>
                                                                        {{ \Carbon\Carbon::parse($row->waktu_berakhir)->format('d/m/Y H:i:s') }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th id="table-head-time_left">Waktu Mulai</th>
                                                                    <td colspan="3">
                                                                        {{ $row->sisa_waktu }} Menit
                                                                    </td>
                                                                </tr>
                                                                @if ($examResult)
                                                                    <tr>
                                                                        <th id="table-head-correct_answer">Soal Terjawab
                                                                        </th>
                                                                        <td>
                                                                            {{ $examResult->terjawab }}
                                                                        </td>
                                                                        <th id="table-head-wrong_answer">Soal Belum Terjawab
                                                                        </th>
                                                                        <td>
                                                                            {{ $examResult->tidak_terjawab }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th id="table-head-correct_answer">Jawaban Benar
                                                                        </th>
                                                                        <td>
                                                                            {{ $examResult->benar }}
                                                                        </td>
                                                                        <th id="table-head-wrong_answer">Jawaban Salah</th>
                                                                        <td>
                                                                            {{ $examResult->salah }}
                                                                        </td>
                                                                    </tr>
                                                                @else
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">
                                                                            <i>Hasil sedang dihitung</i>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            </table>

                                                            @if ($examResult)
                                                                <div class="row mt-2 mb-2"
                                                                    style="white-space: normal; text-align:justify;">
                                                                    @foreach ($examResult->passing_grade as $passing)
                                                                        <div class="col-md-3">
                                                                            <span class="text-primary">Total Nilai :
                                                                                ({{ $passing->alias }})
                                                                            </span> : <b>{{ $passing->total_nilai }}</b>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <hr>

                                                                <div class="mt-1"
                                                                    style="white-space: normal; text-align:justify;">
                                                                    @if ($testimoni)
                                                                        Testimoni : {{ $testimoni->testimoni }}
                                                                        <br>
                                                                        Rating :
                                                                        @for ($i = 0; $i < $testimoni->rating; $i++)
                                                                            <i class="fa fa-star"
                                                                                style="color: rgb(255, 207, 16);"></i>
                                                                        @endfor

                                                                        @if ($testimoni->publish !== 'Y')
                                                                            <br>
                                                                            <button class="btn btn-primary btn-sm mt-2"
                                                                                data-bs-target="#modalTestimoni{{ $no }}"
                                                                                data-bs-toggle="modal">
                                                                                <i class="fa fa-child"></i>
                                                                                Ubah Testimoni
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        <button class="btn btn-primary btn-sm mb-2"
                                                                            data-bs-target="#modalTestimoni{{ $no }}"
                                                                            data-bs-toggle="modal">
                                                                            <i class="fa fa-child"></i> Berikan Testimoni
                                                                        </button><br>
                                                                        <span class="badge bg-warning">
                                                                            Isi Testimoni Untuk Melihat Total Score Anda
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <!-- Change Testimoni modal -->
                                                                <div class="modal fade"
                                                                    id="modalTestimoni{{ $no }}">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content modal-content-demo">
                                                                            <form
                                                                                action="{{ route('ujian.simpan-testimoni') }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('POST')

                                                                                <div class="modal-header">
                                                                                    <h6 class="modal-title"><i
                                                                                            class="fa fa-testimoni"></i>
                                                                                        Berikan Testimoni Hasil Ujian
                                                                                        Anda !
                                                                                    </h6><button aria-label="Close"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        type="button"></button>
                                                                                </div>

                                                                                <div class="modal-body">

                                                                                    <div class="form-group">
                                                                                        <input type="hidden"
                                                                                            class="form-control" required
                                                                                            name="exam_result_id"
                                                                                            value="{{ $examResult->id }}"
                                                                                            readonly>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input type="hidden"
                                                                                            class="form-control" required
                                                                                            name="product_id"
                                                                                            value="{{ $tryout?->id }}"
                                                                                            readonly>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <textarea name="testimoni" id="testimoni" rows="5" class="form-control" required
                                                                                            placeholder="Masukan testimoni anda...">{{ $testimoni->testimoni ?? '' }}</textarea>
                                                                                        @error('testimoni')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}</small>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <select name="rating"
                                                                                            class="form-control"
                                                                                            id="rating" required>
                                                                                            <option value="">
                                                                                                Pilih
                                                                                                Rating Kepuasan</option>
                                                                                            <option value="1"
                                                                                                @if ($testimoni) @if ($testimoni->rating == 1)
                                                                                            selected @endif
                                                                                                @endif>
                                                                                                Sangat Tidak Puas
                                                                                            </option>
                                                                                            <option value="2"
                                                                                                @if ($testimoni) @if ($testimoni->rating == 2)
                                                                                            selected @endif
                                                                                                @endif>
                                                                                                Tidak Puas
                                                                                            </option>
                                                                                            <option value="3"
                                                                                                @if ($testimoni) @if ($testimoni->rating == 3)
                                                                                            selected @endif
                                                                                                @endif>
                                                                                                Cukup Puas
                                                                                            </option>
                                                                                            <option value="4"
                                                                                                @if ($testimoni) @if ($testimoni->rating == 4)
                                                                                            selected @endif
                                                                                                @endif>
                                                                                                Puas
                                                                                            </option>
                                                                                            <option value="5"
                                                                                                @if ($testimoni) @if ($testimoni->rating == 5)
                                                                                            selected @endif
                                                                                                @endif>
                                                                                                Sangat Puas
                                                                                            </option>
                                                                                        </select>
                                                                                        @error('rating')
                                                                                            <small
                                                                                                class="text-danger">{{ $message }}</small>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="modal-footer">
                                                                                    <button
                                                                                        class="btn btn-sm ripple btn-default btn-web"
                                                                                        type="submit"><i
                                                                                            class="fa fa-save"></i>
                                                                                        Simpan</button>
                                                                                    <button
                                                                                        class="btn btn-sm ripple btn-danger"
                                                                                        data-bs-dismiss="modal"
                                                                                        type="button"><i
                                                                                            class="fa fa-times"></i>
                                                                                        Tutup</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End Change Testimoni modal -->
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center;">
                                                            @if ($testimoni)
                                                                <h1>{{ Number::format($examResult->total_nilai, 3) }}</h1>
                                                                Keterangan :
                                                                <span
                                                                    class="badge @if ($examResult->keterangan == 'Gagal') bg-danger @else bg-success @endif">
                                                                    {{ $examResult->keterangan }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Row -->
                @else
                    <div class="row">
                        <div class="col-md-4 d-none d-sm-block">
                            <img width="300px" class="img img-thubmnail"
                                src="{{ asset('resources/images/model-4.png') }}" alt="">
                        </div>
                        <div class="col-md-8" style="vertical-align: middle;">
                            <div class="card custom-card">
                                <div class="card-header p-3 tx-medium my-auto tx-white bg-primary">
                                    Daftar Pengajuan Tryout Gratis Untuk Coba 1 Kali Ujian, Bebas Pilih 1 Kategori (CPNS,
                                    PPK,
                                    Kedinasan)
                                </div>
                                <div class="card-body">
                                    <p class="mg-b-0">
                                        "Kesempatan emas! Dapatkan akses Tryout Gratis dengan mudah. Ajukan sekarang melalui
                                        situs utama kami atau klik tautan di bawah ini dan mulai persiapan Anda menuju
                                        sukses!"
                                        <br>
                                        <br>
                                        <a class="btn btn-primary" href="{{ route('mainweb.index') }}/#coba-gratis">
                                            Pengajuan Tryout Gratis
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
