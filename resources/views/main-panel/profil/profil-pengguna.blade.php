@extends('main-panel.layout.main')
@section('title', 'Vi Star Indonesia | ' . $page_title)
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
                                <form action="{{ route('pengaturan.update-profil') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Perhatian !</strong> Perhatikan pengisian anda sebelum menyimpan data.
                                    </div>
                                    <div class="form-group" hidden>
                                        <label for="userID">User ID <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" placeholder="UserID..."
                                            autocomplete="off" required value="{{ Crypt::encrypt(Auth::user()->id) }}"
                                            id="userID" name="userID" readonly>
                                        @error('userID')
                                            <small class="text-danger">* {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="namaLengkap">Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" placeholder="Nama Lengkap..."
                                            autocomplete="off" required value="{{ Auth::user()->name }}" id="namaLengkap"
                                            name="namaLengkap">
                                        @error('namaLengkap')
                                            <small class="text-danger">* {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control" placeholder="Email..." autocomplete="off"
                                            required value="{{ Auth::user()->email }}" id="email" name="email">
                                        @error('email')
                                            <small class="text-danger">* {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" class="form-control" placeholder="Password..."
                                            autocomplete="off" value="" id="password" name="password">
                                        <small class="text-danger">* Kosongkan jika tidak ingin mengubah</small><br>
                                        @error('password')
                                            <small class="text-danger">* {{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-default btn-sm btn-web">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->

            </div>
        </div>
    </div>
@endsection
