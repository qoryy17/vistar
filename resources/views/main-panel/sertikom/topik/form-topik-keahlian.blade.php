@extends('main-panel.layout.main')
@section('title', $form_title)
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
                                <form action="{{ route('sertikom.expertise-save') }}" method="POST"
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
                                            @if (Crypt::decrypt($param) == 'update' && $expertise)
                                                <div class="form-group" hidden>
                                                    <label for="ID">ID <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" placeholder="ID..."
                                                        autocomplete="off" required
                                                        value="{{ Crypt::encrypt($expertise->id) }}" id="ID"
                                                        name="Id" readonly>
                                                </div>
                                            @endif
                                            <div class="form-group" hidden>
                                                <label for="param">Parameter <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Parameter..."
                                                    autocomplete="off" required value="{{ $param }}" id="param"
                                                    name="param" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="TopikKeahlian">Topik Keahlian <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Topik Keahlian..."
                                                    autocomplete="off" required
                                                    value="{{ $expertise ? $expertise->topik : old('TopikKeahlian') }}"
                                                    id="TopikKeahlian" name="TopikKeahlian">
                                                @error('TopikKeahlian')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Deskripsi">Deskripsi <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="Deskripsi" id="Deskripsi" class="form-control" cols="30" rows="5" placeholder="Deskripsi...">{{ $expertise ? $expertise->deskripsi : old('Deskripsi') }}</textarea>
                                                @error('Deskripsi')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Publish">Publish <span class="text-danger">*</span>
                                                </label>
                                                <select name="Publish" id="Publish" class="form-control" required>
                                                    <option value="">-- Publish ? --</option>
                                                    <option value="Y" @if (old('Publish') == 'Y') selected @endif
                                                        @if ($expertise && $expertise->publish == 'Y') selected @endif>Ya
                                                    </option>
                                                    <option value="T" @if (old('Publish') == 'T') selected @endif
                                                        @if ($expertise && $expertise->publish == 'T') selected @endif>Tidak</option>
                                                </select>
                                                @error('Publish')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
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
                                            <a href="{{ route('sertikom.expertise') }}" class="btn btn-sm btn-dark">
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
