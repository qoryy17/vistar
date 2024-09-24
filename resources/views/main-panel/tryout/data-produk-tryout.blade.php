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
                                        <a href="{{ route('tryouts.form', ['param' => 'add', 'id' => 'tryout']) }}"
                                            class="btn btn-sm btn-default btn-web">
                                            <i class="fa fa-plus"></i> Tambah Produk Tryout
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <td id="no">No</td>
                                                <th id="name">Judul Produk Tryout</th>
                                                <th id="category">Kategori</th>
                                                <th id="price">Harga</th>
                                                <th id="created_at">Dibuat Pada</th>
                                                <td id="actions">Kelola</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($tryouts as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td style="white-space: normal">
                                                        {{ $row->nama_tryout }} <br>
                                                        Status : {{ $row->status }} {{ $row->produk_status }} <br>
                                                        <span class="badge bg-success">
                                                            Passing Grade : {{ $row->passing_grade }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $row->judul }}
                                                    </td>
                                                    <td>
                                                        {{ is_numeric($row->harga) ? Number::currency($row->harga, in: 'IDR') : '-' }}
                                                        <br>
                                                        Promo :
                                                        {{ is_numeric($row->harga_promo) ? Number::currency($row->harga_promo, in: 'IDR') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '-' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('tryouts.soal', ['id' => Crypt::encrypt($row->kode_soal)]) }}"
                                                            title="Soal" class="btn btn-default btn-sm btn-web">
                                                            <i class="fa fa-layer-group"></i>
                                                        </a>
                                                        <button title="Duplikat" class="btn btn-dark btn-sm"
                                                            onclick='swal({
                                                                        title: "Duplikat",
                                                                        text: "Apa anda ingin menduplikat produk tryout ?",
                                                                        type: "info",
                                                                        showCancelButton: true,
                                                                        closeOnConfirm: false,
                                                                        showLoaderOnConfirm: true }, function ()
                                                                        {
                                                                        setTimeout(function(){
                                                                           document.getElementById("duplikasi-form{{ $no }}").submit();
                                                                        }, 2000); });'>
                                                            <i class="fa fa-copy"></i>
                                                        </button>

                                                        <a href="{{ route('tryouts.detail-produk', ['id' => Crypt::encrypt($row->id)]) }}"
                                                            title="Detil" class="btn btn-success btn-sm">
                                                            <i class="fa fa-search"></i>
                                                        </a>
                                                        <a href="{{ route('tryouts.form', ['param' => 'update', 'id' => Crypt::encrypt($row->id)]) }}"
                                                            title="Edit" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-edit"></i>
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
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <form id="delete-form{{ $no }}"
                                                            action="{{ route('tryouts.hapus', ['id' => Crypt::encrypt($row->id)]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <form id="duplikasi-form{{ $no }}"
                                                            action="{{ route('tryouts.duplikat', ['id' => Crypt::encrypt($row->id)]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('POST')
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
