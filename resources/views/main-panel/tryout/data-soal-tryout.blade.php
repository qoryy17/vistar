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
                                <div class="row p-3">
                                    <div class="col-md-12">
                                        <a href="{{ route('tryouts.form-soal', ['param' => 'add', 'questionCode' => $kode_soal]) }}"
                                            class="btn btn-sm btn-default btn-web">
                                            <i class="fa fa-plus"></i> Tambah Soal
                                        </a>
                                        <a href="{{ route('tryouts.index') }}" class="btn btn-sm btn-default btn-web1">
                                            <i class="fa fa-reply"></i> Kembali
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td width="70%">Soal Pertanyaan</td>
                                                <td width="10%" class="text-center">Klasifikasi</td>
                                                <th width="20%" class="text-center">Gambar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($soal as $row)
                                                <tr>
                                                    <td style="vertical-align: top;">{{ $no }}</td>
                                                    <td style="white-space: normal;vertical-align: top;">
                                                        <p style="text-align: justify; margin: 0px; padding: 0px;">
                                                            {!! $row->soal !!}
                                                            <small>
                                                                Created at : {{ $row->created_at }} | Created at :
                                                                {{ $row->updated_at }}
                                                            </small>
                                                        </p>
                                                        <div>
                                                            <a href="#" title="Jawaban" class="btn btn-sm btn-success"
                                                                data-bs-target="#modalPreview{{ $no }}"
                                                                data-bs-toggle="modal">
                                                                <i class="fa fa-layer-group"></i> Jawaban
                                                            </a>
                                                            <!-- Preview modal -->
                                                            <div class="modal fade" id="modalPreview{{ $no }}">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title"><i
                                                                                    class="fa fa-book"></i>
                                                                                Jawaban dan
                                                                                Kunci
                                                                            </h6><button aria-label="Close"
                                                                                class="btn-close" data-bs-dismiss="modal"
                                                                                type="button"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <x-accordion :jawabanA="$row->jawaban_a" :jawabanB="$row->jawaban_b"
                                                                                :jawabanC="$row->jawaban_c" :jawabanD="$row->jawaban_d"
                                                                                :jawabanE="$row->jawaban_e" :berbobot="$row->berbobot"
                                                                                :kunciJawaban="$row->kunci_jawaban" :reviewPembahasan="$row->review_pembahasan" />
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-sm ripple btn-danger"
                                                                                data-bs-dismiss="modal" type="button"><i
                                                                                    class="fa fa-times"></i> Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End Preview modal -->
                                                            <a href="{{ route('tryouts.form-soal', ['param' => 'update', 'questionCode' => $kode_soal, 'questionId' => Crypt::encrypt($row->id)]) }}"
                                                                title="Edit" class="btn btn-sm btn-warning">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </a>
                                                            <button
                                                                onclick='swal({
                                                                            title: "Hapus Data",
                                                                            text: "Data yang dihapus tidak bisa dikembalikan",
                                                                            type: "info",
                                                                            showCancelButton: true,
                                                                            closeOnConfirm: false,
                                                                            showLoaderOnConfirm: true }, function ()
                                                                            {
                                                                            setTimeout(function(){
                                                                                document.getElementById("delete-form{{ $no }}").submit();
                                                                        }, 2000); });'
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fa fa-trash"></i> Hapus
                                                            </button>
                                                            <form id="delete-form{{ $no }}"
                                                                action="{{ route('tryouts.hapus-soal', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col">
                                                                <div class="d-flex gap-2">
                                                                    @if (strval($row->berbobot) === '1')
                                                                        <span>Poin</span>
                                                                        <div class="d-flex flex-1 gap-1 flex-wrap">
                                                                            <div class="badge bg-info text-nowrap">
                                                                                A: <strong>{{ $row->poin_a }}</strong>
                                                                            </div>
                                                                            <div class="badge bg-info text-nowrap">
                                                                                B: <strong>{{ $row->poin_b }}</strong>
                                                                            </div>
                                                                            <div class="badge bg-info text-nowrap">
                                                                                C: <strong>{{ $row->poin_c }}</strong>
                                                                            </div>
                                                                            <div class="badge bg-info text-nowrap">
                                                                                D: <strong>{{ $row->poin_d }}</strong>
                                                                            </div>
                                                                            <div class="badge bg-info text-nowrap">
                                                                                E: <strong>{{ $row->poin_e }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <span>Kunci Jawaban</span>
                                                                        <div class="d-flex flex-1 gap-1 flex-wrap">
                                                                            <div class="badge bg-primary text-nowrap">
                                                                                <strong>{{ $row->kunci_jawaban }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: top; text-align: center;">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span>{{ $row->judul }}</span>
                                                            <span>({{ $row->alias }})</span>
                                                            <span class="badge bg-warning mt-2 inline-block">
                                                                Passing Grade: {{ $row->passing_grade }}
                                                            </span>
                                                            @if ($row->berbobot == 1)
                                                                <span class="badge bg-info mt-2 inline-block">
                                                                    Berbobot
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-center">
                                                        @if ($row->gambar)
                                                            @php
                                                                $image = asset('storage/soal/' . $row->gambar);
                                                            @endphp
                                                            <img src="{{ $image }}" alt="img"
                                                                data-bs-target="#modalImg{{ $no }}"
                                                                data-bs-toggle="modal">
                                                            <!-- Preview modal -->
                                                            <div class="modal fade" id="modalImg{{ $no }}">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title"><i
                                                                                    class="fa fa-book"></i>
                                                                                Gambar Soal
                                                                            </h6><button aria-label="Close"
                                                                                class="btn-close" data-bs-dismiss="modal"
                                                                                type="button"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img src="{{ $image }}"
                                                                                alt="img" />
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-sm ripple btn-danger"
                                                                                data-bs-dismiss="modal" type="button"><i
                                                                                    class="fa fa-times"></i> Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End Preview modal -->
                                                        @else
                                                            <span class="badge bg-warning">Soal Tanpa Gambar</span>
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
