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
                                <form action="{{ route('pengaturan.simpan-web') }}" method="POST"
                                    enctype="multipart/form-data" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Perhatian !</strong> Perhatikan pengisian anda sebelum menyimpan data.
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="namaBisnis">Nama Bisnis <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Nama Bisnis..."
                                                    autocomplete="off" required
                                                    value="{{ $pengaturan->nama_bisnis ?? old('namaBisnis') }}"
                                                    id="namaBisnis" name="namaBisnis">
                                                @error('namaBisnis')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="tagline">Tagline Bisnis <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Tagline Bisnis..."
                                                    autocomplete="off" required
                                                    value="{{ $pengaturan->tagline ?? old('tagline') }}" id="tagline"
                                                    name="tagline">
                                                @error('tagline')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="perusahaan">Perusahaan <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Perusahaan..."
                                                    autocomplete="off" required
                                                    value="{{ $pengaturan->perusahaan ?? old('perusahaan') }}"
                                                    id="perusahaan" name="perusahaan">
                                                @error('perusahaan')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat">Alamat <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="alamat" id="alamat" cols="30" rows="4" class="form-control" id="Nama" required
                                                    placeholder="Alamat..." autocomplete="off">{{ $pengaturan->alamat ?? old('alamat') }}</textarea>
                                                @error('alamat')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" class="form-control" placeholder="Email..."
                                                    autocomplete="off" value="{{ $pengaturan->email ?? old('email') }}"
                                                    id="email" name="email">
                                                @error('email')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="facebook">Facebook <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Facebook..."
                                                    autocomplete="off"
                                                    value="{{ $pengaturan->facebook ?? old('facebook') }}" id="facebook"
                                                    name="facebook">
                                                @error('facebook')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="instagram">Instagram <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Instagram..."
                                                    autocomplete="off"
                                                    value="{{ $pengaturan->instagram ?? old('instagram') }}"
                                                    id="instagram" name="instagram">
                                                @error('instagram')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="kontak">Kontak <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control" placeholder="Kontak..."
                                                    autocomplete="off" value="{{ $pengaturan->kontak ?? old('kontak') }}"
                                                    id="kontak" name="kontak">
                                                @error('kontak')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="logo">Logo<span class="text-danger">*</span>
                                                </label>
                                                <input type="file" name="logo" id="logo" class="dropify"
                                                    data-height="200"
                                                    data-default-file="{{ asset('storage/' . $pengaturan->logo) ?? '' }}" />
                                                <input type="text" hidden class="form-control mt-3"
                                                    placeholder="Logo..." autocomplete="off"
                                                    value="{{ $pengaturan->logo ?? '' }}" readonly id="oldLogo"
                                                    name="oldLogo">
                                                @error('logo')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="metaKeyword">Meta Author <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Meta Author..."
                                                    autocomplete="off"
                                                    value="{{ $pengaturan->meta_author ?? old('metaAuthor') }}"
                                                    id="metaAuthor" name="metaAuthor">
                                                @error('metaAuthor')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="metaKeyword">Meta Keyword <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Meta Keyword..."
                                                    autocomplete="off"
                                                    value="{{ $pengaturan->meta_keyword ?? old('metaKeyword') }}"
                                                    id="metaKeyword" name="metaKeyword">
                                                @error('metaKeyword')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="metaDescription">Meta Description <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control"
                                                    placeholder="Meta Description..." autocomplete="off"
                                                    value="{{ $pengaturan->meta_description ?? old('metaDescription') }}"
                                                    id="metaDescription" name="metaDescription">
                                                @error('metaDescription')
                                                    <small class="text-danger"> {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-sm btn-default btn-web">
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
