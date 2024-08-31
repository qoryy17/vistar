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
                                <form action="{{ route('tryouts.simpan-soal') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="text-wrap">
                                        <div class="example">
                                            <div class="panel panel-primary tabs-style-1">
                                                <div class=" tab-menu-heading">
                                                    <div class="tabs-menu1">
                                                        <!-- Tabs -->
                                                        <ul class="nav panel-tabs main-nav-line">
                                                            <li class="nav-item"><a href="#soal" class="nav-link active"
                                                                    data-bs-toggle="tab">Soal Pertanyaan</a></li>
                                                            <li class="nav-item"><a href="#jawabanA" class="nav-link"
                                                                    data-bs-toggle="tab">Jawaban A</a></li>
                                                            <li class="nav-item"><a href="#jawabanB" class="nav-link"
                                                                    data-bs-toggle="tab">Jawaban B</a></li>
                                                            <li class="nav-item"><a href="#jawabanC" class="nav-link"
                                                                    data-bs-toggle="tab">Jawaban C</a></li>
                                                            <li class="nav-item"><a href="#jawabanD" class="nav-link"
                                                                    data-bs-toggle="tab">Jawaban D</a></li>
                                                            <li class="nav-item"><a href="#review" class="nav-link"
                                                                    data-bs-toggle="tab">Kunci Jawaban & Review
                                                                    Pembahasan</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                @if ($soal)
                                                    @foreach ($soal as $row)
                                                    @endforeach
                                                @endif
                                                <div
                                                    class="panel-body tabs-menu-body main-content-body-right border-top-0 border">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="soal">
                                                            @if (Crypt::decrypt($formParam) == 'update')
                                                                <div class="form-group" hidden>
                                                                    <label for="idSoal">Soal ID <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" readonly name="idSoal"
                                                                        class="form-control"
                                                                        value="{{ Crypt::encrypt($row->id) }}">
                                                                </div>
                                                            @endif
                                                            <div class="form-group" hidden>
                                                                <label for="KodeSoal">Kode Soal <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" readonly name="kodeSoal"
                                                                    class="form-control" value="{{ $kode_soal }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Klasifikasi">Klasifikasi <span
                                                                        class="text-danger">*</span></label>
                                                                <select class="form-control selectKlasifikasi"
                                                                    name="klasifikasi" id="Klasifikasi" required>
                                                                    <option value="">-- Pilih Klasifikasi --
                                                                    </option>
                                                                    @foreach ($klasifikasi_soal as $klasifikasi)
                                                                        @if ($soal)
                                                                            @if ($row->klasifikasi_soal_id == $klasifikasi->id)
                                                                                <option selected
                                                                                    value="{{ $klasifikasi->id }}">
                                                                                    {{ $klasifikasi->judul }}
                                                                                    ({{ $klasifikasi->alias }})
                                                                                </option>
                                                                            @else
                                                                                <option value="{{ $klasifikasi->id }}">
                                                                                    {{ $klasifikasi->judul }}
                                                                                    ({{ $klasifikasi->alias }})
                                                                                </option>
                                                                            @endif
                                                                        @else
                                                                            @if (old('klasifikasi') == $klasifikasi->id)
                                                                                <option selected
                                                                                    value="{{ $klasifikasi->id }}">
                                                                                    {{ $klasifikasi->judul }}
                                                                                    ({{ $klasifikasi->alias }})
                                                                                </option>
                                                                            @else
                                                                                <option value="{{ $klasifikasi->id }}">
                                                                                    {{ $klasifikasi->judul }}
                                                                                    ({{ $klasifikasi->alias }})
                                                                                </option>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                @error('klasifikasi')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Soal">Soal Pertanyaan <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea required id="Soal" class="contentSoal" name="soal">{{ $soal ? $row->soal : old('soal') }}</textarea>
                                                                @error('klasifikasi')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="SoalGambar">Soal Gambar
                                                                    <small> (Unggah jika ada/ Max. 2MB)</small>
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="file" id="SoalGambar" class="dropify"
                                                                    data-height="200"
                                                                    data-default-file= "{{ $soal ? asset('soal/' . $row->gambar) : '' }}"
                                                                    name="gambar" />
                                                                @error('gambar')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group" hidden>
                                                                <label for="formParameter">Form Parameter <span
                                                                        class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Parameter..." autocomplete="off" required
                                                                    id="formParameter" name="formParameter" required
                                                                    value="{{ $formParam }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="jawabanA">
                                                            <div class="form-group">
                                                                <label for="JawabanA">Jawaban A <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea class="contentJawabanA" id="JawabanA" required name="jawabanA">{{ $soal ? $row->jawaban_a : old('jawabanA') }}</textarea>
                                                                @error('jawabanA')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="jawabanB">
                                                            <div class="form-group">
                                                                <label for="JawabanB">Jawaban B <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea class="contentJawabanB" id="JawabanB" required name="jawabanB">{{ $soal ? $row->jawaban_b : old('jawabanB') }}</textarea>
                                                                @error('jawabanB')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="jawabanC">
                                                            <div class="form-group">
                                                                <label for="JawabanC">Jawaban C <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea class="contentJawabanC" id="JawabanC" required name="jawabanC">{{ $soal ? $row->jawaban_c : old('jawabanC') }}</textarea>
                                                                @error('jawabanC')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="jawabanD">
                                                            <div class="form-group">
                                                                <label for="JawabanD">Jawaban D <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea class="contentJawabanD" id="JawabanD" required name="jawabanD">{{ $soal ? $row->jawaban_d : old('jawabanD') }}</textarea>
                                                                @error('jawabanD')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="review">
                                                            <div class="form-group">
                                                                <label for="poin">Poin
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" id="poin" name="poin"
                                                                    required
                                                                    value="{{ $soal ? $row->poin : old('poin') }}"
                                                                    class="form-control" autocomplete="off">
                                                                @error('poin')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            @if ($soal)
                                                            @else
                                                                @if (old('kunciJawaban') == 'A')
                                                                    selected
                                                                @endif
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="Kunci">Kunci Jawaban <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="kunciJawaban" class="form-control"
                                                                    id="Kunci" required>
                                                                    <option value="">-- Pilih Kunci Jawaban --
                                                                    </option>
                                                                    <option value="A"
                                                                        @if ($soal) @if ($row->kunci_jawaban == 'A') selected @endif
                                                                    @else
                                                                        @if (old('kunciJawaban') == 'A') selected @endif
                                                                        @endif>
                                                                        A
                                                                    </option>
                                                                    <option value="B"
                                                                        @if ($soal) @if ($row->kunci_jawaban == 'B') selected @endif
                                                                    @else
                                                                        @if (old('kunciJawaban') == 'B') selected @endif
                                                                        @endif>
                                                                        B
                                                                    </option>
                                                                    <option value="C"
                                                                        @if ($soal) @if ($row->kunci_jawaban == 'C') selected @endif
                                                                    @else
                                                                        @if (old('kunciJawaban') == 'C') selected @endif
                                                                        @endif>
                                                                        C
                                                                    </option>
                                                                    <option value="D"
                                                                        @if ($soal) @if ($row->kunci_jawaban == 'D') selected @endif
                                                                    @else
                                                                        @if (old('kunciJawaban') == 'D') selected @endif
                                                                        @endif>
                                                                        D
                                                                    </option>
                                                                </select>
                                                                @error('kunciJawaban')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="ReviewPembahasan">Review Pembahasan <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea class="contentReviewPembahasan" id="ReviewPembahasan" required name="reviewPembahasan">{{ $soal ? $row->review_pembahasan : old('reviewPembahasan') }}</textarea>
                                                                @error('reviewPembahasan')
                                                                    <small class="text-danger">*
                                                                        {{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                            <a href="{{ route('tryouts.soal', ['id' => $kode_soal]) }}"
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
