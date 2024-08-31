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
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
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
                                                <td>No</td>
                                                <th>Logs Aktivitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($logs as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td style="white-space: normal">
                                                        <h5>{{ $row->name }} ({{ $row->user_id }})</h5>
                                                        <p>
                                                            IP Address : {{ $row->ip_address }} <br>
                                                            User Agent : {{ $row->user_agent }}
                                                        </p>
                                                        <p>Aktivitas : {{ $row->aktivitas }}</p>
                                                        <span>Created at : {{ $row->created_at }} | Updated at :
                                                            {{ $row->updated_at }}</span>
                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
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
                                                                    Hapus
                                                                </button>
                                                                <form id="delete-form{{ $no }}"
                                                                    action="{{ route('pengaturan.hapus-logs', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>
                                                        </div>
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
