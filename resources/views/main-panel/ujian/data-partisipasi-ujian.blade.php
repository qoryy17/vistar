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
                                        <a title="Tambah Partisipasi" class="btn btn-sm btn-primary"
                                            href="{{ route('exam-special.form', ['param' => Crypt::encrypt('add'), 'id' => Crypt::encrypt('exam')]) }}">
                                            <i class="fa fa-plus"></i> Tambah Partisipan
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk Tryout</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Kelola</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($products as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>
                                                        {{ $row->nama_tryout }}
                                                    </td>
                                                    <td>
                                                        {{ $row->created_at }}
                                                    </td>
                                                    <td>
                                                        {{ $row->updated_at }}
                                                    </td>
                                                    <td>
                                                        <a title="Detail {{ $row->nama_tryout }}"
                                                            class="btn btn-sm btn-primary"
                                                            href="{{ route('exam-special.participants', ['id' => Crypt::encrypt($row->idProduk)]) }}">
                                                            <i class="fa fa-database"></i>
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
