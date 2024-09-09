@extends('main-panel.layout.main')
@section('title', 'Vi Star | Manajemen Pengguna')
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
                                        <a href="{{ route('user.form-user', ['param' => 'add', 'id' => 'pengguna']) }}"
                                            class="btn btn-sm btn-default btn-web">
                                            <i class="fa fa-plus"></i> Tambah Pengguna
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th width="2%">No</th>
                                                <th width="98%">Identitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($users->get() as $row)
                                                <tr>
                                                    <td style="vertical-align: top;">{{ $no }} </td>
                                                    <td style="vertical-align: top;">
                                                        <h5 style="color: #0075B8;">
                                                            {{ $row->name }} <small>({{ $row->email }})</small>
                                                        </h5>
                                                        Email Verified At : {{ $row->email_verified_at }} <br>
                                                        Role : {{ $row->role }} | Blokir : <span class="badge bg-dark">
                                                            {{ $row->blokir }}</span>
                                                        <br>
                                                        Created at : {{ $row->created_at }} | Updated at :
                                                        {{ $row->updated_at }}


                                                        <div class="row mt-2">
                                                            <div class="col-md-12">
                                                                <button type="button" class="btn btn-sm btn-dark"
                                                                    data-bs-target="#modalUbahPassword{{ $no }}"
                                                                    data-bs-toggle="modal"><i class="fa fa-unlock"></i>
                                                                    Ubah
                                                                    Password</button>
                                                                @if ($row->blokir == 'Y')
                                                                    <button onclick="submitForm{{ $no }}()"
                                                                        class="btn btn-sm btn-success"><i
                                                                            class="fa fa-globe"></i> Unblokir</button>
                                                                @else
                                                                    <button onclick="submitForm{{ $no }}()"
                                                                        type="submit" class="btn btn-sm btn-warning"><i
                                                                            class="fa fa-globe"></i> Blokir</button>
                                                                @endif

                                                                <a href="{{ route('user.form-user', ['param' => 'update', 'id' => Crypt::encrypt($row->id)]) }}"
                                                                    class="btn btn-sm btn-warning"><i
                                                                        class="fa fa-edit"></i> Edit</a>
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
                                                                    class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-trash"></i> Hapus
                                                                </a>
                                                                <form id="delete-form{{ $no }}"
                                                                    action="{{ route('user.hapus', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                <form
                                                                    action="{{ route('user.blokir', ['id' => Crypt::encrypt($row->id)]) }}"
                                                                    method="POST" id="formBlok{{ $no }}">
                                                                    @csrf
                                                                    @method('POST')
                                                                </form>
                                                                <script>
                                                                    function submitForm{{ $no }}() {
                                                                        document.getElementById("formBlok{{ $no }}").submit();
                                                                    }
                                                                </script>
                                                            </div>

                                                        </div>
                                                        <!-- Change Password modal -->
                                                        <form action="{{ route('user.ubah-password') }}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="modal fade"
                                                                id="modalUbahPassword{{ $no }}">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title"><i
                                                                                    class="fa fa-unlock"></i>
                                                                                Ubah
                                                                                Password
                                                                            </h6><button aria-label="Close"
                                                                                class="btn-close" data-bs-dismiss="modal"
                                                                                type="button"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group" hidden>
                                                                                <label for="userID">UserID
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control"
                                                                                    required placeholder="*******"
                                                                                    autocomplete="off" name="userID"
                                                                                    id="userID" readonly
                                                                                    value="{{ $row->id }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="password">Password Baru
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="password" class="form-control"
                                                                                    required placeholder="*******"
                                                                                    autocomplete="off" name="password"
                                                                                    id="password" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button
                                                                                class="btn btn-sm ripple btn-default btn-web"
                                                                                type="submit"><i class="fa fa-save"></i>
                                                                                Simpan</button>
                                                                            <button class="btn btn-sm ripple btn-danger"
                                                                                data-bs-dismiss="modal" type="button"><i
                                                                                    class="fa fa-times"></i>
                                                                                Tutup</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <!-- End Change Password modal -->
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
