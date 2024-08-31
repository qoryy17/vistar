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
                                                <th width="2%">No</th>
                                                <th width="98%">Testimoni</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($testimoni as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td style="white-space: normal">
                                                        <h5 style="color: #0075B8; ">{{ $row->nama_lengkap }} (Hasil Ujian
                                                            ID :
                                                            {{ $row->hasil_ujian_id }})
                                                            @if ($row->publish == 'Y')
                                                                <sup>Publish : <small class="badge bg-success">Ya</small>
                                                                </sup>
                                                            @else
                                                                <sup>Publish : <small class="badge bg-danger">Tidak</small>
                                                                </sup>
                                                            @endif
                                                            <br>
                                                        </h5>
                                                        Created at : {{ $row->created_at }} | Updated at
                                                        : {{ $row->updated_at }}
                                                        <div class="line-top-web mt-2"
                                                            style="white-space: normal; text-align:justify;">
                                                            <p class="mt-2">
                                                                Produk : <strong>{{ $row->nama_tryout }}</strong> <br>
                                                                Testimoni : {{ $row->testimoni }} <br>
                                                                Rating :
                                                                @for ($i = 0; $i < $row->rating; $i++)
                                                                    <i class="fa fa-star"
                                                                        style="color: rgb(255, 207, 16);"></i>
                                                                @endfor
                                                            </p>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                @if ($row->publish == 'Y')
                                                                    <a href="{{ route('testimoni.publish', ['id' => Crypt::encrypt($row->id), 'publish' => Crypt::encrypt('T')]) }}"
                                                                        class="btn btn-sm btn-warning"><i
                                                                            class="fa fa-globe"></i> Publish (Tidak)
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('testimoni.publish', ['id' => Crypt::encrypt($row->id), 'publish' => Crypt::encrypt('Y')]) }}"
                                                                        class="btn btn-sm btn-success"><i
                                                                            class="fa fa-globe"></i> Publish (Ya)
                                                                    </a>
                                                                @endif

                                                                <button
                                                                    onclick='swal({
                                                                            title: "Hapus Data",
                                                                            text: "Data yang dihapus tidak bisa dikembalikan \n Nilai ujian peserta kembali tidak bisa dilihat !",
                                                                            type: "info",
                                                                            showCancelButton: true,
                                                                            closeOnConfirm: false,
                                                                            showLoaderOnConfirm: true }, function () 
                                                                            { 
                                                                            setTimeout(function(){  
                                                                                document.getElementById("delete-form{{ $no }}").submit();
                                                                        }, 2000); });'
                                                                    class="btn btn-sm btn-danger"><i
                                                                        class="fa fa-trash"></i> Hapus
                                                                </button>
                                                                <form id="delete-form{{ $no }}"
                                                                    action="{{ route('testimoni.hapus', ['id' => Crypt::encrypt($row->id)]) }}"
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
