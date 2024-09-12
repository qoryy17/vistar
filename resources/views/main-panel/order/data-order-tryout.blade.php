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
                                        <button type="button" data-bs-target="#modalPrint" data-bs-toggle="modal"
                                            class="btn btn-sm btn-default btn-web">
                                            <i class="fa fa-print"></i> Cetak List Order Tryout
                                        </button>
                                        <button type="button" data-bs-target="#modalExcel" data-bs-toggle="modal"
                                            class="btn btn-sm btn-success">
                                            <i class="fa fa-file-excel"></i> Export Excel
                                        </button>
                                        <!-- Print modal -->
                                        <form action="{{ route('listOrders.export-to-pdf') }}" method="GET">
                                            @csrf
                                            @method('GET')
                                            <div class="modal fade" id="modalPrint">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title"><i class="fa fa-file-pdf"></i>
                                                                Export Pdf
                                                            </h6><button aria-label="Close" class="btn-close"
                                                                data-bs-dismiss="modal" type="button"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="tanggalAwal">Tanggal Awal <span
                                                                                class="tx-danger">*</span></label>
                                                                        <input class="form-control"
                                                                            placeholder="Tanggal Awal" type="text"
                                                                            id="datepicker-date" required autocomplete="off"
                                                                            name="tanggalAwal">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="tanggalAkhir">Tanggal Akhir <span
                                                                                class="tx-danger">*</span></label>
                                                                        <input class="form-control"
                                                                            placeholder="Tanggal Akhir" type="text"
                                                                            id="datepicker-date1" required
                                                                            autocomplete="off" name="tanggalAkhir">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-sm ripple btn-default btn-web"
                                                                type="submit"><i class="fa fa-file-excel"></i>
                                                                Export</button>
                                                            <button class="btn btn-sm ripple btn-danger"
                                                                data-bs-dismiss="modal" type="button"><i
                                                                    class="fa fa-times"></i> Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- End Print modal -->
                                        <!-- Excel modal -->
                                        <form action="{{ route('listOrders.export-to-excel') }}" method="GET">
                                            @csrf
                                            @method('GET')
                                            <div class="modal fade" id="modalExcel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title"><i class="fa fa-file-excel"></i>
                                                                Export Excel
                                                            </h6><button aria-label="Close" class="btn-close"
                                                                data-bs-dismiss="modal" type="button"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="tanggalAwal">Tanggal Awal <span
                                                                                class="tx-danger">*</span></label>
                                                                        <input class="form-control"
                                                                            placeholder="Tanggal Awal" type="text"
                                                                            id="datepicker-date2" required
                                                                            autocomplete="off" name="tanggalAwal">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="tanggalAkhir">Tanggal Akhir <span
                                                                                class="tx-danger">*</span></label>
                                                                        <input class="form-control"
                                                                            placeholder="Tanggal Akhir" type="text"
                                                                            id="datepicker-date3" required
                                                                            autocomplete="off" name="tanggalAkhir">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-sm ripple btn-default btn-web"
                                                                type="submit"><i class="fa fa-file-excel"></i>
                                                                Export</button>
                                                            <button class="btn btn-sm ripple btn-danger"
                                                                data-bs-dismiss="modal" type="button"><i
                                                                    class="fa fa-times"></i> Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- End Excel modal -->
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Order</td>
                                                <th>ID Pembayaran</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <td>Kelola</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($order as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>
                                                        <h6>Order ID : {{ $row->id }}</h6>
                                                        <p>
                                                            Faktur : <strong>{{ $row->faktur_id }}</strong> <br>
                                                            Produk : <strong>{{ $row->nama_tryout }}</strong> <br>
                                                            Peserta : <strong>{{ $row->nama }}</strong> <br>
                                                            Status Order : @if ($row->status_order == 'pending')
                                                                <strong
                                                                    class="text-warning">{{ $row->status_order }}</strong>
                                                            @elseif ($row->status_order == 'paid')
                                                                <strong
                                                                    class="text-success">{{ $row->status_order }}</strong>
                                                            @endif
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <a href="">
                                                            {{ $row->payment_id }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $row->created_at }}
                                                    </td>
                                                    <td>
                                                        {{ $row->updated_at }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('listOrders.detil', ['orderID' => $row->id]) }}"
                                                            title="Detil" class="btn btn-default btn-sm btn-web">
                                                            <i class="fa fa-search"></i>
                                                        </a>

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
