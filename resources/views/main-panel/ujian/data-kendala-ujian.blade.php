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
                                <div class="p-3">
                                    <form method="GET">
                                        <div class="d-flex gap-2 mb-3 align-items-center justify-content-center">
                                            <label class="fw-bold mb-0" for="status">Status</label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="">-- Semua --</option>
                                                @foreach ($statusList as $statusKey => $statusValue)
                                                    <option value="{{ $statusKey }}"
                                                        {{ $status === $statusKey ? 'selected' : '' }}>
                                                        {{ $statusValue }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit"
                                                class="btn bg-primary d-flex gap-2 align-items-center justify-content-center">
                                                <i class="fe fe-search"></i>
                                                <span>Filter</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Laporan</th>
                                                <th>Status</th>
                                                <th>Lampiran</th>
                                                <th>Dilaporkan Pada</th>
                                                <th>Diperbarui Pada</th>
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
                                                        <table>
                                                            <tr>
                                                                <th id="report_id">ID Laporan</th>
                                                                <td><span class="badge bg-info">{{ $row->id }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th id="product_name">Produk</th>
                                                                <td>
                                                                    <span class="fw-bold text-wrap">
                                                                        {{ $row->nama_tryout }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="d-flex flex-column gap-1">
                                                                        <div class="fw-bold">
                                                                            Deksripsi Masalah
                                                                        </div>
                                                                        <div class="text-wrap">
                                                                            {{ $row->deskripsi }}
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                        <a href="{{ route('tryouts.form-soal', ['param' => 'update', 'questionCode' => Crypt::encrypt($row->kode_soal), 'questionId' => Crypt::encrypt($row->soal_id)]) }}"
                                                            class="btn btn-primary btn-sm mt-2"
                                                            title="Klik untuk edit soal !">
                                                            Lihat Soal
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $row->status === \App\Enums\ReportExamStatus::FIXED->value ? 'bg-success' : 'bg-warning' }}">
                                                            {{ @$statusList[$row->status] }}
                                                        </span>
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
                                                                <div class="modal-dialog modal-lg">
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
                                                                            <img src="{{ asset('storage/' . $row->screenshot) }}"
                                                                                class="img-fluid"
                                                                                alt="Screenshot Laporan ID: {{ $row->id }}"
                                                                                title="Screenshot Laporan ID: {{ $row->id }}"
                                                                                loading="lazy" />
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
                                                        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($row->updated_at)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>
                                                        @if ($row->status === \App\Enums\ReportExamStatus::WAITING->value)
                                                            <a title="Validasi"
                                                                href="{{ route('report.validated-exam', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                        @elseif ($row->status === \App\Enums\ReportExamStatus::FIXED->value)
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
