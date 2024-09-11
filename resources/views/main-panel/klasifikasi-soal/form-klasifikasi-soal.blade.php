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
                            <li class="breadcrumb-item">{{ $bc1 }}</li>
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
                                <form action="{{ route('klasifikasi.simpan') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Perhatian !</strong> Perhatikan pengisian anda sebelum menyimpan data.
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            @if (Crypt::decrypt($formParam) == 'update')
                                                <div class="form-group" hidden>
                                                    <label for="klasifikasiID">
                                                        Klasifikasi Soal ID <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="klasifikasiID..." autocomplete="off" required
                                                        value="{{ @$klasifikasi->id }}" id="klasifikasiID"
                                                        name="klasifikasiID" readonly>
                                                    @error('klasifikasiID')
                                                        <small class="text-danger">* {{ $message }}</small>
                                                    @enderror
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="JudulKlasifikasiSoal">
                                                    Judul Klasifikasi Soal <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control"
                                                    placeholder="Judul Klasifikasi Soal..." autocomplete="off"
                                                    value="{{ old('judul', @$klasifikasi->judul) }}"
                                                    id="JudulKlasifikasiSoal" name="judul" required>
                                                @error('judul')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Alias">
                                                    Alias <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Alias Singkatan..."
                                                    autocomplete="off" value="{{ old('alias', @$klasifikasi->alias) }}"
                                                    id="Alias" name="alias" required>
                                                @error('alias')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="PassingGrade">
                                                    Passing Grade <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control" placeholder="Passing Grade..."
                                                    autocomplete="off" min="0"
                                                    value="{{ old('passingGrade', @$klasifikasi->passing_grade) }}"
                                                    id="PassingGrade" name="passingGrade" required />
                                                @error('passingGrade')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="ordering">
                                                    Urutan <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control" placeholder="Urutan..."
                                                    autocomplete="off" min="1"
                                                    value="{{ old('ordering', @$klasifikasi->ordering) ?? 1 }}"
                                                    id="ordering" name="ordering" required />
                                                @error('ordering')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Aktif">Aktif <span class="text-danger">*</span>
                                                </label>
                                                <select name="aktif" id="Aktif" class="form-control" required>
                                                    <option value="">-- Aktif ? --</option>
                                                    @php
                                                        $active = 'Y';
                                                        if ($klasifikasi) {
                                                            $active = $klasifikasi->aktif;
                                                        }
                                                        $active = old('aktif', $active);
                                                    @endphp
                                                    <option value="Y" {{ $active === 'Y' ? 'selected' : '' }}>
                                                        Ya
                                                    </option>
                                                    <option value="T" {{ $active === 'T' ? 'selected' : '' }}>
                                                        Tidak
                                                    </option>
                                                </select>
                                                @error('aktif')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group" hidden>
                                                <label for="formParameter">
                                                    Form Parameter <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Parameter..."
                                                    autocomplete="off" id="formParameter" name="formParameter"
                                                    value="{{ $formParam }}" required readonly />
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
                                            <a href="{{ route('klasifikasi.index') }}" class="btn btn-sm btn-dark">
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
