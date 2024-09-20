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
                            <li class="breadcrumb-item"><a href="#">{{ $bc2 }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $bc3 }}</li>
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
                                        <a href="{{ route('exam-special.participants', ['id' => Crypt::encrypt($backLink)]) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fa fa-reply"></i> Kembali
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-2" id="example1">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Produk Tryout</th>
                                                <th>Keterangan</th>
                                                <th>Kelola</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($examTryout as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $row->nama_tryout }}</td>
                                                    <td>{{ $row->keterangan }}</td>
                                                    <td>
                                                        <a title="Tambah Partisipasi" class="btn btn-sm btn-warning"
                                                            href="{{ route('exam-special.form', ['param' => Crypt::encrypt('update'), 'id' => Crypt::encrypt($row->id)]) }}">
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
                                                            action="{{ route('exam-special.delete', ['id' => Crypt::encrypt($row->id), 'param' => Crypt::encrypt('half')]) }}"
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
