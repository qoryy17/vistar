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
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th width="1%">No</th>
                                                <th width="89%">Informasi Permohonan</th>
                                                <th width="5%">Created At</th>
                                                <th width="5%">Valdiasi Bukti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($permohonanTryout as $row)
                                                <tr>
                                                    <td style="vertical-align: top;">1</td>
                                                    <td style="vertical-align: top;">
                                                        <p>
                                                            Produk : <b>{{ $row->nama_tryout }}</b><br>
                                                            Kategori Produk : {{ $row->status }} <br>
                                                            Customer : <b>{{ $row->nama_lengkap }}</b>
                                                        </p>
                                                        <hr>
                                                        <p>
                                                            Informasi Didapat : {{ $row->informasi }} <br>
                                                            Alasan : {{ $row->alasan }}
                                                        </p>
                                                        @if ($row->status_validasi == 'Menunggu')
                                                            Status Validasi : <span class="badge bg-warning">Menunggu</span>
                                                        @elseif($row->status_validasi == 'Disetujui')
                                                            Status Validasi : <span
                                                                class="badge bg-success">Disetujui</span>
                                                            <small>TimeStamp : {{ $row->updated_at }}</small>
                                                        @elseif($row->status_validasi == 'Ditolak')
                                                            Status Validasi : <span class="badge bg-danger">Ditolak</span>
                                                            <small>TimeStamp : {{ $row->updated_at }}</small>
                                                        @endif
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        {{ $row->created_at }}
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <button class="btn btn-sm btn-success"
                                                            data-bs-target="#modalPreview" data-bs-toggle="modal">
                                                            <i class="fa fa-check"></i> Validasi
                                                        </button>
                                                        <!-- Preview modal -->
                                                        <form
                                                            action="{{ route('tryouts.validasi-pengajuan-tryout-gratis') }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @method('POST')
                                                            @csrf
                                                            <div class="modal fade" id="modalPreview">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title"><i
                                                                                    class="fa fa-file"></i>
                                                                                Validasi Bukti Share dan Bukti Follow
                                                                            </h6><button aria-label="Close"
                                                                                class="btn-close" data-bs-dismiss="modal"
                                                                                type="button"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <x-accordion-bukti :buktiShare="$row->bukti_share"
                                                                                :buktiFollow="$row->bukti_follow" />
                                                                            <div class="form-group mt-3">
                                                                                <input type="hidden" readonly
                                                                                    value="{{ Crypt::encrypt($row->id) }}"
                                                                                    class="form-control" name="id">
                                                                            </div>
                                                                            <div class="form-group mt-3">
                                                                                <select name="validasi" id="validasi"
                                                                                    class="form-control" required>
                                                                                    <option value="">Pilih Status
                                                                                        Validasi
                                                                                    </option>
                                                                                    <option value="Disetujui"
                                                                                        @if ($row->status_validasi == 'Disetujui') selected @endif>
                                                                                        Disetujui
                                                                                    </option>
                                                                                    <option value="Ditolak"
                                                                                        @if ($row->status_validasi == 'Ditolak') selected @endif>
                                                                                        Ditolak</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-sm ripple btn-primary"
                                                                                type="submit"><i class="fa fa-check"></i>
                                                                                Validasi</button>
                                                                            <button class="btn btn-sm ripple btn-danger"
                                                                                data-bs-dismiss="modal" type="button"><i
                                                                                    class="fa fa-times"></i> Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <!-- End Preview modal -->
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
