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
                                <div class="row m-1 mt-2 mb-3">
                                    <div class="col-md-12">
                                        <button type="button" data-bs-target="#modalPrint" data-bs-toggle="modal"
                                            class="btn btn-sm btn-default btn-web1">
                                            <i class="fa fa-print"></i> Cetak Customer
                                        </button>
                                        <!-- Print modal -->
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="modal fade" id="modalPrint">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content modal-content-demo">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title"><i class="fa fa-file-pdf"></i> Cetak
                                                                Customer
                                                            </h6><button aria-label="Close" class="btn-close"
                                                                data-bs-dismiss="modal" type="button"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="Pendidikan">Pendidikan</label>
                                                                <select name="Pendidikan" id="Pendidikan"
                                                                    class="form-control">
                                                                    <option value="">-- Pilih Pendidikan --</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Jurusan">Jurusan</label>
                                                                <select name="Jurusan" id="Jurusan" class="form-control">
                                                                    <option value="">-- Pilih Jurusan --</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="TahunDaftar">Tahun Terdaftar</label>
                                                                <select name="TahunDaftar" id="TahunDaftar"
                                                                    class="form-control">
                                                                    <option value="">-- Pilih Tahun Terdaftar --
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-sm ripple btn-default btn-web"
                                                                type="button"><i class="fa fa-print"></i> Cetak</button>
                                                            <button class="btn btn-sm ripple btn-danger"
                                                                data-bs-dismiss="modal" type="button"><i
                                                                    class="fa fa-times"></i> Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- End Print modal -->
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th width="1%">No</th>
                                                <th width="79%">Identitas</th>
                                                <th width="20%">Foto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                use Carbon\Carbon;
                                                $no = 1;

                                            @endphp
                                            @foreach ($customer as $row)
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <h5 style="color: #0075B8; ">{{ $row['nama_lengkap'] }}
                                                            ({{ $row['email'] }})
                                                        </h5>
                                                        {{ $row['pendidikan'] }} - {{ $row['jurusan'] }}<br>
                                                        Tanggal Lahir :
                                                        {{ is_null($row['tanggal_lahir']) ? '' : Carbon::parse($row['tanggal_lahir'])->translatedFormat('l, d F Y') }}
                                                        / Jenis Kelamin :
                                                        {{ $row['jenis_kelamin'] }}
                                                        <br>
                                                        Kontak :
                                                        {{ $row['kontak'] }} <br>
                                                        Alamat : {{ $row['alamat'] }} <br>
                                                        {{ $row['kecamatan'] }}
                                                        {{ $row['kabupaten'] }}
                                                        {{ $row['provinsi'] }}
                                                        <br>
                                                        Blokir : <span class="badge bg-warning">
                                                            {{ $row['blokir'] }}</span>
                                                        <br>
                                                        Created at : {{ $row['created_at'] }} | Updated at :
                                                        {{ $row['updated_at'] }}

                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                @if ($row['blokir'] == 'Y')
                                                                    <button onclick="submitForm{{ $no }}()"
                                                                        class="btn btn-sm btn-success"><i
                                                                            class="fa fa-globe"></i> Unblokir</button>
                                                                @else
                                                                    <button onclick="submitForm{{ $no }}()"
                                                                        type="submit" class="btn btn-sm btn-warning"><i
                                                                            class="fa fa-globe"></i> Blokir</button>
                                                                @endif
                                                                <a href="#"
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
                                                                    class="btn btn-sm btn-danger"><i
                                                                        class="fa fa-trash"></i> Hapus</a>
                                                            </div>
                                                        </div>
                                                        <form id="delete-form{{ $no }}"
                                                            action="{{ route('customer.hapus', ['id' => Crypt::encrypt($row['user_id'])]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <form
                                                            action="{{ route('customer.blokir', ['id' => Crypt::encrypt($row['user_id'])]) }}"
                                                            method="POST" id="formBlok{{ $no }}">
                                                            @csrf
                                                            @method('POST')
                                                        </form>
                                                        <script>
                                                            function submitForm{{ $no }}() {
                                                                document.getElementById("formBlok{{ $no }}").submit();
                                                            }
                                                        </script>
                                                    </td>
                                                    <td>
                                                        @if (!is_null($row['foto']))
                                                            <img src="{{ asset('storage/user/' . $row['foto']) }}"
                                                                alt="user" class="img img-thumbnail">
                                                        @endif

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
