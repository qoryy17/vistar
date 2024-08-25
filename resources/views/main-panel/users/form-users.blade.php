@extends('main-panel.layout.main')
@section('title', 'Vi Star | ' . $form_title)
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
                            <li class="breadcrumb-item">Manajemen Pengguna</li>
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
                                <form action="{{ route('user.simpan') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Perhatian !</strong> Perhatikan pengisian anda sebelum menyimpan data.
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if (Crypt::decrypt($formParam) == 'update')
                                                <div class="form-group" hidden>
                                                    <label for="userID">User ID <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" placeholder="UserID..."
                                                        autocomplete="off" required
                                                        value="{{ old('userID') ?? $users->id }}" id="userIDLengkap"
                                                        name="userID">
                                                    @error('userID')
                                                        <small class="text-danger">* {{ $message }}</small>
                                                    @enderror
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="namaLengkap">Nama Lengkap <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Nama Lengkap..."
                                                    autocomplete="off" required
                                                    value="{{ $users->name ?? old('namaLengkap') }}" id="namaLengkap"
                                                    name="namaLengkap">
                                                @error('namaLengkap')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                @if (Crypt::decrypt($formParam) == 'update')
                                                    <label for="password">Password Baru <span class="text-danger">*
                                                            <small>(Kosongkan jika tidak ingin mengganti)</small></span>
                                                    </label><br>
                                                    <small>(Password harus minimal 8 karakter,
                                                        mengandung huruf kapital, angka dan karakter)</small>
                                                @else
                                                    <label for="password">Password <span class="text-danger">*</span>
                                                    </label><br>
                                                    <small>(Password harus minimal 8 karakter,
                                                        mengandung huruf kapital, angka dan karakter)</small>
                                                @endif
                                                <input type="password" class="form-control" placeholder="******"
                                                    autocomplete="off" value="{{ old('password') }}" id="password"
                                                    name="password">
                                                @error('password')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" class="form-control" placeholder="Email..."
                                                    autocomplete="off" required value="{{ $users->email ?? old('email') }}"
                                                    id="email" name="email">
                                                @error('email')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role">Role <span class="text-danger">*</span>
                                                </label>
                                                <select name="role" id="role" class="form-control" required>
                                                    <option value="">-- Pilih Role --</option>
                                                    @if ($users)
                                                        @if ($users->role == 'Superadmin')
                                                            <option value="Superadmin" selected>Superadmin</option>
                                                            <option value="Finance">Finance </option>
                                                        @elseif ($users->role == 'Admin')
                                                            <option value="Admin" selected>Admin</option>
                                                            <option value="Finance">Finance </option>
                                                        @elseif ($users->role == 'Finance')
                                                            <option value="Admin">Admin</option>
                                                            <option value="Finance" selected>Finance </option>
                                                        @else
                                                            <option value="Superadmin">Superadmin</option>
                                                            <option value="Admin">Admin</option>
                                                            <option value="Finance">Finance </option>
                                                        @endif
                                                    @else
                                                        @if (old('role') == 'Superadmin')
                                                            <option value="Superadmin" selected>Superadmin</option>
                                                            <option value="Finance">Finance </option>
                                                        @elseif (old('role') == 'Admin')
                                                            <option value="Admin" selected>Admin</option>
                                                            <option value="Finance">Finance </option>
                                                        @elseif (old('role') == 'Finance')
                                                            <option value="Admin">Admin</option>
                                                            <option value="Finance" selected>Finance </option>
                                                        @else
                                                            <option value="Superadmin">Superadmin</option>
                                                            <option value="Admin">Admin</option>
                                                            <option value="Finance">Finance </option>
                                                        @endif
                                                    @endif
                                                </select>
                                                @error('role')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="blokir">Blokir <span class="text-danger">*</span>
                                                </label>
                                                <select name="blokir" id="blokir" class="form-control" required>
                                                    <option value="">-- Blokir ? --</option>
                                                    @if ($users)
                                                        @if ($users->blokir == 'Y')
                                                            <option value="Y" selected>Ya</option>
                                                            <option value="T">Tidak</option>
                                                        @elseif ($users->blokir == 'T')
                                                            <option value="Y">Ya</option>
                                                            <option value="T" selected>Tidak</option>
                                                        @else
                                                            <option value="Y">Ya</option>
                                                            <option value="T">Tidak</option>
                                                        @endif
                                                    @else
                                                        @if (old('blokir') == 'Y')
                                                            <option value="Y" selected>Ya</option>
                                                            <option value="T">Tidak</option>
                                                        @elseif (old('blokir') == 'T')
                                                            <option value="Y">Ya</option>
                                                            <option value="T" selected>Tidak</option>
                                                        @else
                                                            <option value="Y">Ya</option>
                                                            <option value="T">Tidak</option>
                                                        @endif
                                                    @endif
                                                </select>
                                                @error('blokir')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group" hidden>
                                                <label for="formParameter">Form Parameter <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Parameter..."
                                                    autocomplete="off" required id="formParameter" name="formParameter"
                                                    required value="{{ $formParam }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-default btn-sm btn-web">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <button type="reset" class="btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Ulang
                                            </button>
                                            <a href="{{ route('user.main') }}" class="btn btn-sm btn-dark">
                                                <i class="fa fa-reply"></i> Kembali
                                            </a>
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
