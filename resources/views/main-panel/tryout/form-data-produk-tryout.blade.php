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
                                <div class="alert alert-warning" role="alert">
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Perhatian !</strong> Perhatikan pengisian anda sebelum menyimpan data.
                                </div>

                                <form action="{{ route('tryouts.simpan') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if (Crypt::decrypt($formParam) == 'update')
                                                <div class="form-group" hidden>
                                                    <label for="produkID">Produk Tryout ID <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" required id="produkID" autocomplete="off"
                                                        class="form-control" value="{{ $tryout->id }}" name="produkID"
                                                        readonly>
                                                    @error('produkID')
                                                        <small class="text-danger">* {{ $message }}</small>
                                                    @enderror
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="Nama">Nama Tryout <span class="text-danger">*</span></label>
                                                <input type="text" required id="Nama" autocomplete="off"
                                                    class="form-control"
                                                    value="{{ $tryout->nama_tryout ?? old('namaTryout') }}"
                                                    name="namaTryout">
                                                @error('namaTryout')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Keterangan">Keterangan Tryout <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="keterangan" id="Keterangan" required autocomplete="off" class="form-control">{{ $tryout->keterangan ?? old('keterangan') }}</textarea>
                                                @error('keterangan')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="Status">
                                                            Status <span class="text-danger">*</span>
                                                        </label>
                                                        <select class="form-control" name="status" id="Status" required>
                                                            <option value="">-- Pilih Status --</option>
                                                            <option value="Tersedia"
                                                                {{ old('status', @$tryout?->status) === 'Tersedia' ? 'selected' : '' }}>
                                                                Tersedia
                                                            </option>
                                                            <option value="Tidak Tersedia"
                                                                {{ old('status', @$tryout?->status) === 'Tidak Tersedia' ? 'selected' : '' }}>
                                                                Tidak Tersedia</option>
                                                        </select>
                                                        @error('status')
                                                            <small class="text-danger">* {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="Kategori">Kategori <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control" name="kategori" id="Kategori"
                                                            required>
                                                            <option value="">-- Pilih Kategori --</option>
                                                            @foreach ($kategori->get() as $kategoriProduk)
                                                                <option value="{{ $kategoriProduk->id }}"
                                                                    {{ strval(old('kategori', @$tryout?->kategori_produk_id)) === strval($kategoriProduk->id) ? 'selected' : '' }}>
                                                                    {{ $kategoriProduk->judul }}
                                                                    ({{ $kategoriProduk->status }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('kategori')
                                                            <small class="text-danger">* {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="passingGrade">Passing Grade SKD
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="passingGrade" name="passingGrade" required
                                                    value="{{ $pengaturan->passing_grade ?? old('passingGrade') }}"
                                                    class="form-control" autocomplete="off">
                                                @error('passingGrade')
                                                    <small class="text-danger">*
                                                        {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Harga">Harga Tryout <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" inputmode="numeric" required id="Harga"
                                                    name="harga" autocomplete="off" class="form-control"
                                                    value="{{ $pengaturan->harga ?? old('harga') }}">
                                                @error('harga')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="HargaPromo">Harga Promo Tryout <small>(Kosong jika tidak ada
                                                        promo)</small><span class="text-danger">*</span></label>
                                                <input type="text" inputmode="numeric" id="HargaPromo"
                                                    autocomplete="off" class="form-control" name="hargaPromo"
                                                    value="{{ $pengaturan->harga_promo ?? old('hargaPromo') }}">
                                                @error('hargaPromo')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="Durasi">Durasi Ujian Tryout / Menit <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" inputmode="numeric" required id="Durasi"
                                                    autocomplete="off" class="form-control" name="durasiUjian"
                                                    value="{{ $pengaturan->durasi ?? old('durasiUjian') }}">
                                                @error('durasiUjian')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="MasaAktif">Masa Aktif Tryout / Hari <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" inputmode="numeric" required id="MasaAktif"
                                                    autocomplete="off" class="form-control" name="masaAktif"
                                                    value="{{ $pengaturan->masa_aktif ?? old('masaAktif') }}">
                                                @error('masaAktif')
                                                    <small class="text-danger">* {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="Pengaturan">
                                                    Pengaturan Tambahan
                                                    <span class="text-danger">*</span>
                                                </label>
                                                @php
                                                    $additionalConfig = [
                                                        'nilaiKeluar' => [
                                                            'db_key' => 'nilai_keluar',
                                                            'title' => 'Nilai Keluar',
                                                        ],
                                                        'grafikEvaluasi' => [
                                                            'db_key' => 'grafik_evaluasi',
                                                            'title' => 'Grafik Evaluasi',
                                                        ],
                                                        'reviewPembahasan' => [
                                                            'db_key' => 'review_pembahasan',
                                                            'title' => 'Review Pembahasan',
                                                        ],
                                                        'ulangUjian' => [
                                                            'db_key' => 'ulang_ujian',
                                                            'title' => 'Ulang Ujian',
                                                        ],
                                                    ];
                                                @endphp
                                                <div class="d-flex gap-2" style="overflow-x: auto;">
                                                    @foreach ($additionalConfig as $configKey => $configValue)
                                                        <div class="form-group">
                                                            <label for="additional-config-{{ $configKey }}"
                                                                class="ckbox" style="white-space: nowrap;">
                                                                <input id="additional-config-{{ $configKey }}"
                                                                    type="checkbox" name="{{ $configKey }}"
                                                                    value="Y"
                                                                    {{ old($configKey, $pengaturan ? $pengaturan[$configValue['db_key']] : null) === 'Y' ? 'checked' : '' }} />
                                                                <span class="ps-1">{{ $configValue['title'] }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Thumbnail">Thumbail @if ($tryout)
                                                        <small>(Kosongkan jika tidak ingin
                                                            mengganti/ Max. 2MB)</small>
                                                    @endif <span class="text-danger">*</span></label>
                                                <input type="file" id="Thumbnail" class="dropify" data-height="190"
                                                    name="thumbnail"
                                                    data-default-file= "{{ $tryout ? asset('storage/' . $tryout->thumbnail) : '' }}" />
                                                @error('thumbnail')
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
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-default btn-sm btn-web">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <button type="reset" class="btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Ulang
                                            </button>
                                            <a href="{{ route('tryouts.index') }}" class="btn btn-sm btn-dark">
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
