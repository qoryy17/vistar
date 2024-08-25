@extends('main-panel.layout.main')
@section('title', 'Vi Star | ' . $page_title)
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
                                <form action="{{ route('kategori.simpan') }}" method="POST" enctype="multipart/form-data">
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
                                                    <label for="kategoriID">Kategori ID <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" placeholder="kategoriID..."
                                                        autocomplete="off" required
                                                        value="{{ old('kategoriID') ?? $kategori->id }}" id="kategoriID"
                                                        name="kategoriID" readonly>
                                                    @error('kategoriID')
                                                        <small class="text-danger">* {{ $message }}</small>
                                                    @enderror
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="JudulKategori">Judul Kategori <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Judul Kategori..."
                                                    autocomplete="off" required
                                                    value="{{ $kategori->judul ?? old('judul') }}" id="JudulKategori"
                                                    name="judul">
                                                @error('judul')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Status">Status <span class="text-danger">*</span>
                                                </label>
                                                <select name="status" id="Status" class="form-control" required>
                                                    <option value="">-- Pilih Status ? --</option>
                                                    @if ($kategori)
                                                        @if ($kategori->status == 'Gratis')
                                                            <option value="Gratis" selected>Gratis</option>
                                                            <option value="Berbayar">Berbayar</option>
                                                        @elseif ($kategori->status == 'Berbayar')
                                                            <option value="Gratis">Gratis</option>
                                                            <option value="Berbayar" selected>Berbayar</option>
                                                        @else
                                                            <option value="Gratis">Gratis</option>
                                                            <option value="Berbayar">Berbayar</option>
                                                        @endif
                                                    @else
                                                        @if (old('status') == 'Gratis')
                                                            <option value="Gratis" selected>Gratis</option>
                                                            <option value="Berbayar">Berbayar</option>
                                                        @elseif (old('status') == 'Berbayar')
                                                            <option value="Gratis">Gratis</option>
                                                            <option value="Berbayar" selected>Berbayar</option>
                                                        @else
                                                            <option value="Gratis">Gratis</option>
                                                            <option value="Berbayar">Berbayar</option>
                                                        @endif
                                                    @endif
                                                </select>
                                                @error('status')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Aktif">Aktif <span class="text-danger">*</span>
                                                </label>
                                                <select name="aktif" id="Aktif" class="form-control" required>
                                                    <option value="">-- Aktif ? --</option>
                                                    @if ($kategori)
                                                        @if ($kategori->aktif == 'Y')
                                                            <option value="Y" selected>Ya</option>
                                                            <option value="T">Tidak</option>
                                                        @elseif ($kategori->aktif == 'T')
                                                            <option value="Y">Ya</option>
                                                            <option value="T" selected>Tidak</option>
                                                        @endif
                                                    @else
                                                        @if (old('aktif') == 'Y')
                                                            <option value="Y" selected>Ya</option>
                                                            <option value="T">Tidak</option>
                                                        @elseif (old('aktif') == 'T')
                                                            <option value="Y">Ya</option>
                                                            <option value="T" selected>Tidak</option>
                                                        @else
                                                            <option value="Y">Ya</option>
                                                            <option value="T">Tidak</option>
                                                        @endif
                                                    @endif
                                                </select>
                                                @error('aktif')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group" hidden>
                                                <label for="formParameter">Form Parameter <span class="text-danger">*</span>
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
                                            <a href="{{ route('kategori.index', ['produk' => 'tryout']) }}"
                                                class="btn btn-sm btn-dark">
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
