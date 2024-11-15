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
                                <form action="{{ route('sertikom.workshop-save') }}" method="POST"
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
                                        <div class="col-md-6">
                                            @if (Crypt::decrypt($param) == 'update' && $sertikom)
                                                <div class="form-group" hidden>
                                                    <label for="ID">ID <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" placeholder="ID..."
                                                        autocomplete="off" required
                                                        value="{{ Crypt::encrypt($sertikom->id) }}" id="ID"
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
                                                <label for="NamaWorkshop">Nama Workshop <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Nama Workshop..."
                                                    autocomplete="off" required
                                                    value="{{ $sertikom ? $sertikom->produk : old('NamaWorkshop') }}"
                                                    id="NamaWorkshop" name="NamaWorkshop">
                                                @error('NamaWorkshop')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Harga">Harga <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control" placeholder="Harga..."
                                                    autocomplete="off" required
                                                    value="{{ $sertikom ? $sertikom->harga : old('Harga') }}" id="Harga"
                                                    name="Harga">
                                                @error('Harga')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Deskripsi">Deskripsi <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="Deskripsi" id="Deskripsi" class="form-control" cols="30" rows="5" placeholder="Deskrispi...">{{ $sertikom ? $sertikom->deskripsi : old('Deskripsi') }}</textarea>
                                                @error('Deskripsi')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Instruktur">Instruktur <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control selectInstructor" name="Instruktur"
                                                    id="Instruktur" required>
                                                    <option value="">Pilih Instruktur</option>
                                                    @foreach ($instructor as $instruktur)
                                                        <option value="{{ $instruktur->id }}"
                                                            @if (old('Instruktur') == $instruktur->id) selected @else @if ($sertikom && $sertikom->instruktur_id == $instruktur->id)
                                                                selected @endif
                                                            @endif>
                                                            {{ $instruktur->instruktur }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('Instruktur')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Kategori">Kategori <span class="text-danger">*</span></label>
                                                <select class="form-control" name="Kategori" id="Kategori" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach ($categoryProduct as $category)
                                                        <option value="{{ $category->id }}"
                                                            @if (old('Kategori') == $category->id) selected @else @if ($sertikom && $sertikom->kategori_produk_id == $category->id)
                                                                selected @endif
                                                            @endif>
                                                            {{ $category->judul }}
                                                            ({{ $category->status }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('Kategori')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="TopikKeahlian">Topik Keahlian
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control selectExpertise" name="TopikKeahlian"
                                                    id="TopikKeahlian" required>
                                                    <option value="">-- Pilih Topik Keahlian --</option>
                                                    @foreach ($expertise as $expertiseSkill)
                                                        <option value="{{ $expertiseSkill->id }}"
                                                            @if (old('TopikKeahlian') == $expertiseSkill->id) selected @else @if ($sertikom && $sertikom->topik_keahlian_id == $expertiseSkill->id)
                                                                selected @endif
                                                            @endif>
                                                            {{ $expertiseSkill->topik }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('TopikKeahlian')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="TanggalMulai">Tanggal Mulai
                                                            <span class="tx-danger">*</span>
                                                        </label>
                                                        <input class="form-control" placeholder="Tanggal Mulai..."
                                                            type="text" id="datepicker-date" required
                                                            autocomplete="off" name="TanggalMulai"
                                                            value="{{ old('TanggalMulai') ? \Carbon\Carbon::createFromFormat('Y-m-d', old('TanggalMulai'))->format('d-m-Y') : '' }} {{ $sertikom ? \Carbon\Carbon::createFromFormat('Y-m-d', $sertikom->tanggal_mulai)->format('d-m-Y') : '' }}">
                                                        @error('TanggalMulai')
                                                            <small class="text-danger">* {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="TanggalSelesai">Tanggal Selesai
                                                            <span class="tx-danger">*</span>
                                                        </label>
                                                        <input class="form-control" placeholder="Tanggal Selesai..."
                                                            type="text" id="datepicker-date1" required
                                                            autocomplete="off" name="TanggalSelesai"
                                                            value="{{ old('TanggalMulai') ? \Carbon\Carbon::createFromFormat('Y-m-d', old('TanggalSelesai'))->format('d-m-Y') : '' }} {{ $sertikom ? \Carbon\Carbon::createFromFormat('Y-m-d', $sertikom->tanggal_selesai)->format('d-m-Y') : '' }}">
                                                        @error('TanggalSelesai')
                                                            <small class="text-danger">* {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="timeStart">Jam Mulai
                                                            <span class="tx-danger">*</span>
                                                        </label>
                                                        <input class="form-control" placeholder="Jam Mulai..."
                                                            type="text" id="timeStart" required autocomplete="off"
                                                            name="JamMulai"
                                                            value="{{ $sertikom ? $sertikom->jam_mulai : old('JamMulai') }}">
                                                        @error('JamMulai')
                                                            <small class="text-danger">* {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="timeEnd">Jam Selesai
                                                            <span class="tx-danger">*</span>
                                                        </label>
                                                        <input class="form-control" placeholder="Jam Selesai..."
                                                            type="text" id="timeEnd" required autocomplete="off"
                                                            name="JamSelesai"
                                                            value="{{ $sertikom ? $sertikom->jam_selesai : old('JamSelesai') }}">
                                                        @error('JamSelesai')
                                                            <small class="text-danger">* {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Thumbnail">Thumbnail @if ($sertikom)
                                                        <small>(Kosongkan jika tidak ingin
                                                            mengganti/ Max. 2MB)</small>
                                                    @endif <span class="text-danger">*</span></label>
                                                <input type="file" id="Thumbnail" class="dropify" data-height="185"
                                                    name="thumbnail"
                                                    data-default-file= "{{ $sertikom ? asset('storage/' . $sertikom->thumbnail) : '' }}" />
                                                @error('thumbnail')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Publish">Publish <span class="text-danger">*</span>
                                                </label>
                                                <select name="Publish" id="Publish" class="form-control" required>
                                                    <option value="">-- Publish Produk ? --</option>
                                                    <option value="Y"
                                                        @if (old('Publish') == 'Y') selected @else @if ($sertikom && $sertikom->publish == 'Y') selected @endif
                                                        @endif>Ya</option>
                                                    <option value="T"
                                                        @if (old('Publish') == 'T') selected @else @if ($sertikom && $sertikom->publish == 'T') selected @endif
                                                        @endif>
                                                        Tidak</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="LinkZoom">Link Zoom
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Link Zoom..."
                                                    autocomplete="off" required
                                                    value="{{ $sertikom ? $sertikom->link_zoom : old('LinkZoom') }}"
                                                    id="LinkZoom" name="LinkZoom">
                                                @error('LinkZoom')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="LinkWA">Link WhatsApp
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Link WhatsApp..."
                                                    autocomplete="off" required
                                                    value="{{ $sertikom ? $sertikom->link_wa : old('LinkWA') }}"
                                                    id="LinkWA" name="LinkWA">
                                                @error('LinkWA')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="LinkRekaman">Link Rekaman
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" placeholder="Link Rekaman..."
                                                    autocomplete="off" required
                                                    value="{{ $sertikom ? $sertikom->link_rekaman : old('LinkRekaman') }}"
                                                    id="LinkRekaman" name="LinkRekaman">
                                                @error('LinkRekaman')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Status">Status <span class="text-danger">*</span>
                                                </label>
                                                <select name="Status" id="Status" class="form-control" required>
                                                    <option value="">-- Status Produk --</option>
                                                    <option value="Tersedia"
                                                        @if (old('Status') == 'Tersedia') selected @else @if ($sertikom && $sertikom->status == 'Tersedia') selected @endif
                                                        @endif>Tersedia</option>
                                                    <option value="Sold Out"
                                                        @if (old('Status') == 'Sold Out') selected @else @if ($sertikom && $sertikom->status == 'Sold Out') selected @endif
                                                        @endif>Sold Out</option>
                                                </select>
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
                                            <a href="{{ route('sertikom.product', ['category' => 'workshop']) }}"
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
