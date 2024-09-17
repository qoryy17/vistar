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
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Status</th>
                                                <th>Screenshot</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Kelola</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($reportExam as $row)
                                                <tr>
                                                    <td style="vertical-align: top">{{ $no }}</td>
                                                    <td>
                                                        <p>
                                                            Produk : <strong>{{ $row->nama_tryout }}</strong> <br>
                                                            Deksripsi Masalah : {{ $row->deskripsi }} <br>
                                                            <a href="{{ route('tryouts.form-soal', ['param' => 'update', 'questionCode' => Crypt::encrypt($row->kode_soal), 'questionId' => Crypt::encrypt($row->soal_id)]) }}"
                                                                class="btn btn-primary btn-sm mt-2"
                                                                title="Klik untuk edit soal !">Lihat Soal ID :
                                                                {{ $row->soal_id }}
                                                            </a>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        @if ($row->status == 'Waiting')
                                                            <span class="badge bg-warning">{{ $row->status }}</span>
                                                        @elseif ($row->status == 'Fixed')
                                                            <span class="badge bg-success">{{ $row->status }}</span>
                                                        @else
                                                            {{ $row->status }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button data-bs-target="#modalPreview{{ $no }}"
                                                            data-bs-toggle="modal" class="btn btn-sm btn-warning">Lihat
                                                            Screenshot</button>
                                                        <!-- Preview modal -->
                                                        <form
                                                            action="{{ route('tryouts.validasi-pengajuan-tryout-gratis') }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @method('POST')
                                                            @csrf
                                                            <div class="modal fade" id="modalPreview{{ $no }}">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title">
                                                                                <i class="fa fa-image"></i>
                                                                                Screenshot Kendala Soal Ujian
                                                                            </h6>
                                                                            <button aria-label="Close" class="btn-close"
                                                                                data-bs-dismiss="modal"d
                                                                                type="button"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img loading="lazy"
                                                                                src="{{ asset('storage/ujian/' . $row->screenshot) }}"
                                                                                alt="Screenshot" class="img-fluid">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-md btn-block btn-danger"
                                                                                data-bs-dismiss="modal" type="button">
                                                                                <i class="fa fa-times"></i> Tutup
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <!-- End Preview modal -->
                                                    </td>
                                                    <td>
                                                        {{ $row->created_at }}
                                                    </td>
                                                    <td>
                                                        {{ $row->updated_at }}
                                                    </td>
                                                    <td>
                                                        @if ($row->status == 'Waiting')
                                                            <a title="Validasi"
                                                                href="{{ route('report.validated-exam', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        @elseif ($row->status == 'Fixed')
                                                            <a title="Batalkan validasi"
                                                                href="{{ route('report.validated-exam', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                class="btn btn-sm btn-warning">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        @endif

                                                        <button
                                                            onclick='swal({
                                                                    title: "Hapus Data",
                                                                    text: "Data yang dihapus tidak bisa dikembalikan",
                                                                    type: "info",
                                                                    showCancelButton: true,
                                                                    closeOnConfirm: true,
                                                                    showLoaderOnConfirm: true }, function ()
                                                                    {
                                                                        setTimeout(function(){
                                                                        document.getElementById("delete-form{{ $no }}").submit();
                                                                    }, 2000); });'
                                                            class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <form id="delete-form{{ $no }}"
                                                            action="{{ route('report.delete-exam', ['id' => Crypt::encrypt($row->id)]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
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
