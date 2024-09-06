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
                <!-- End Page Header -->

                @if ($pembelian->first())
                    <div class="row">
                        @foreach ($pembelian->get() as $tryout)
                            @php
                                $cekUjian = App\Models\Ujian::all()
                                    ->where('order_tryout_id', $tryout->id)
                                    ->first();

                                $date = \Carbon\Carbon::parse($tryout->created_at);
                                $newDate = $date->addDays($tryout->masa_aktif);
                                $masaAktif = now()->diffInDays($newDate, false);
                            @endphp
                            @if ($masaAktif > 0)
                                {{-- Informasi Paket Tryout --}}
                                <div class="col-md-4">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div>
                                                <h6>Informasi Paket</h6>
                                                <h6 style="padding: 0px; margin:0px;" class="mb-2">
                                                    <span class="fs-25 me-2 text-primary">{{ $tryout->nama_tryout }}</span>
                                                    <span class="badge bg-success mt-2">
                                                        {{ Number::currency($tryout->harga, in: 'IDR') }}
                                                    </span>
                                                </h6>
                                                <span class="text-muted tx-12">
                                                    Klik tombol dibawah untuk melanjutkan ujian Tryout berbasis CBT/CAT
                                                </span>
                                                @if ($cekUjian)
                                                    @if ($cekUjian->status_ujian == 'Sedang Dikerjakan')
                                                        <button onclick='document.getElementById("mulaiUjian").submit();'
                                                            class="btn btn-block btn-default mt-2 btn-web1">
                                                            <i class="fa fa-check-circle"></i>
                                                            Lanjut Mengerjakan
                                                        </button>
                                                        <small class="text-danger">Sisa Masa Aktif Paket :
                                                            {{ ceil($masaAktif) }} Hari</small>
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
                                                            document.getElementById("mulaiUjian").submit();
                                                    }, 1000); });'
                                                        class="btn btn-block btn-default mt-2 btn-web">
                                                        <i class="fa fa-check-circle"></i>
                                                        Mulai Ujian
                                                    </button>
                                                    <small class="text-danger">Sisa Masa Aktif Paket :
                                                        {{ ceil($masaAktif) }} Hari</small>
                                                @endif

                                                <form id="mulaiUjian"
                                                    action="{{ route('ujian.main', ['id' => Crypt::encrypt($tryout->id), 'param' => Crypt::encrypt('berbayar')]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($masaAktif == 0)
                                {{-- Informasi Paket Tryout --}}
                                <div class="col-md-4">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div>
                                                <h6>Informasi Paket</h6>
                                                <h6 style="padding: 0px; margin:0px;" class="mb-2">
                                                    <span class="fs-25 me-2 text-primary">{{ $tryout->nama_tryout }}</span>
                                                    <span class="badge bg-success mt-2">
                                                        {{ Number::currency($tryout->harga, in: 'IDR') }}
                                                    </span>
                                                </h6>
                                                <span class="text-muted tx-12">
                                                    Klik tombol dibawah untuk melanjutkan ujian Tryout berbasis CBT/CAT
                                                </span>
                                                @if ($cekUjian)
                                                    @if ($cekUjian->status_ujian == 'Sedang Dikerjakan')
                                                        <button onclick='document.getElementById("mulaiUjian").submit();'
                                                            class="btn btn-block btn-default mt-2 btn-web1">
                                                            <i class="fa fa-check-circle"></i>
                                                            Lanjut Mengerjakan
                                                        </button>
                                                        <small class="text-danger">Masa Aktif Berkahir Hari Ini</small>
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
                                                            document.getElementById("mulaiUjian").submit();
                                                    }, 1000); });'
                                                        class="btn btn-block btn-default mt-2 btn-web">
                                                        <i class="fa fa-check-circle"></i>
                                                        Mulai Ujian
                                                    </button>
                                                    <small class="text-danger">Masa Aktif Berkahir Hari Ini</small>
                                                @endif

                                                <form id="mulaiUjian"
                                                    action="{{ route('ujian.main', ['id' => Crypt::encrypt($tryout->id), 'param' => Crypt::encrypt('berbayar')]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Informasi Paket Tryout --}}
                                <div class="col-md-4">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div>
                                                <h6>Informasi Paket</h6>
                                                <h6 style="padding: 0px; margin:0px;" class="mb-2">
                                                    <span class="fs-25 me-2 text-primary">{{ $tryout->nama_tryout }}</span>
                                                    <span class="badge bg-success mt-2">
                                                        {{ Number::currency($tryout->harga, in: 'IDR') }}
                                                    </span>
                                                </h6>
                                                <span class="text-muted tx-12">
                                                    Masa Aktif Paket Telah Berakhir : {{ abs(ceil($masaAktif)) }} Hari Yang
                                                    Lalu
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
                <!-- Row -->
                <div class="row sidemenu-height">
                    <div class="col-lg-12">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Informasi Ujian</td>
                                                <td class="text-center">Total Nilai</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($hasilUjian as $row)
                                                <tr>
                                                    <td style="vertical-align: top;">1</td>
                                                    <td>
                                                        <a href="{{ route('ujian.hasil', ['id' => Crypt::encrypt($row->id), 'ujianID' => Crypt::encrypt($row->ujianID), 'produkID' => Crypt::encrypt($row->produk_tryout_id)]) }}"
                                                            title="Klik untuk melihat detil">
                                                            <h5 style="color: #0075B8;">Exam - {{ $row->id }}</h5>
                                                        </a>
                                                        <div>
                                                            Waktu Mulai : <b>{{ $row->waktu_mulai }}</b> | Selesai :
                                                            <b>{{ $row->durasi_selesai }}</b> <br>
                                                            Sisa Waktu : <b>{{ $row->sisa_waktu }} Menit</b> <br>
                                                            Soal Benar : <b>{{ $row->benar }}</b> | Soal Salah :
                                                            <b>{{ $row->salah }}</b> <br>
                                                            Soal Terjawab : <b>{{ $row->terjawab }}</b> | Soal Tidak
                                                            Terjawab : <b>{{ $row->tidak_terjawab }}</b>
                                                            <hr>
                                                            <div class="row mt-2 mb-2"
                                                                style="white-space: normal; text-align:justify;">
                                                                @php
                                                                    $passingGrade = App\Models\HasilPassingGrade::where(
                                                                        'hasil_ujian_id',
                                                                        $row->id,
                                                                    )
                                                                        ->orderBy('judul', 'DESC')
                                                                        ->get();
                                                                @endphp
                                                                @foreach ($passingGrade as $passing)
                                                                    <div class="col-md-3">
                                                                        <span class="text-primary">Total Nilai :
                                                                            ({{ $passing->alias }})
                                                                        </span> : <b>{{ $passing->total_nilai }}</b>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        @php
                                                            $testimoni = App\Models\Testimoni::where(
                                                                'customer_id',
                                                                Auth::user()->customer_id,
                                                            )
                                                                ->where('hasil_ujian_id', $row->id)
                                                                ->first();
                                                        @endphp
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
                                                                <br>
                                                                <button class="btn btn-primary btn-sm mt-2"
                                                                    data-bs-target="#modalTestimoni{{ $no }}"
                                                                    data-bs-toggle="modal">
                                                                    <i class="fa fa-child"></i>
                                                                    Ubah Testimoni
                                                                </button>
                                                            @else
                                                                <button class="btn btn-primary btn-sm mb-2"
                                                                    data-bs-target="#modalTestimoni{{ $no }}"
                                                                    data-bs-toggle="modal"><i class="fa fa-child"></i>
                                                                    Berikan
                                                                    Testimoni</button><br>
                                                                <span class="badge bg-warning">Silahkan Isi Testimoni Untuk
                                                                    Melihat
                                                                    Total Score
                                                                    Nilai</span>
                                                            @endif
                                                            <!-- Change Testimoni modal -->
                                                            <form action="{{ route('ujian.simpan-testimoni') }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal fade"
                                                                    id="modalTestimoni{{ $no }}">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content modal-content-demo">
                                                                            <div class="modal-header">
                                                                                <h6 class="modal-title"><i
                                                                                        class="fa fa-testimoni"></i>
                                                                                    Berikan Testimoni Hasil Ujian Anda !
                                                                                </h6><button aria-label="Close"
                                                                                    class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    type="button"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <input type="hidden"
                                                                                        class="form-control" required
                                                                                        name="ujianID"
                                                                                        value="{{ $row->id }}"
                                                                                        readonly>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input type="hidden"
                                                                                        class="form-control" required
                                                                                        name="produkID"
                                                                                        value="{{ Crypt::encrypt($row->produk_tryout_id) }}"
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
                                                                                        <option value="">Pilih
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
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <!-- End Change Testimoni modal -->
                                                        </div>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if ($testimoni)
                                                            <h1>{{ Number::format($row->skd, 3) }}</h1>
                                                            Keterangan :
                                                            <span
                                                                class="badge @if ($row->keterangan == 'Gagal') bg-danger @else bg-success @endif">{{ $row->keterangan }}</span>
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
            </div>
        </div>
    </div>
@endsection
