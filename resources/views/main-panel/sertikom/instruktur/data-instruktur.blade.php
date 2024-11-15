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
                                        <a href="{{ route('sertikom.instructor-form', ['param' => 'add', 'id' => Crypt::encrypt('null')]) }}"
                                            class="btn btn-sm btn-default btn-web">
                                            <i class="fa fa-plus"></i> Tambah Instruktur
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Instruktur</th>
                                                <th>Deskripsi</th>
                                                <th>Publish</th>
                                                <th>Dibuat Pada</th>
                                                <th>Diperbarui</th>
                                                <th>Kelola</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($instructor as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $row->instruktur }} <br> Keahlian : {{ $row->keahlian }}</td>
                                                    <td>{{ $row->deskripsi }}</td>
                                                    <td>
                                                        @if ($row->publish == 'Y')
                                                            <span class="badge bg-success">Ya</span>
                                                        @else
                                                            <span class="badge bg-danger">Tidak</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->created_at }}</td>
                                                    <td>{{ $row->updated_at }}</td>
                                                    <td>
                                                        <a href="{{ route('sertikom.instructor-form', ['param' => 'edit', 'id' => Crypt::encrypt($row->id)]) }}"
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
                                                            action="{{ route('sertikom.instructor-delete', ['id' => Crypt::encrypt($row->id)]) }}"
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
