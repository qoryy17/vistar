@extends('main-panel.layout.main')
@section('title', $page_title)
@section('content')
    @php
        $options = ['a', 'b', 'c', 'd', 'e'];
    @endphp

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

                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        @foreach ($errors->all() as $error)
                                            <p>{!! $error !!}</p>
                                        @endforeach
                                    </div>
                                @endif
                                <form action="{{ route('tryouts.simpan-soal') }}" method="POST"
                                    enctype="multipart/form-data" id="save-question">
                                    @csrf
                                    @method('POST')
                                    <div class="text-wrap">
                                        <div class="example">
                                            <div class="panel panel-primary tabs-style-1">
                                                <div class=" tab-menu-heading">
                                                    <div class="tabs-menu1">
                                                        <!-- Tabs -->
                                                        <ul class="nav panel-tabs main-nav-line">
                                                            <li class="nav-item">
                                                                <a href="#soal" class="nav-link active"
                                                                    data-bs-toggle="tab">Soal Pertanyaan</a>
                                                            </li>

                                                            @foreach ($options as $option)
                                                                <li class="nav-item">
                                                                    <a href="#tab-jawaban-{{ $option }}"
                                                                        class="nav-link" data-bs-toggle="tab">
                                                                        Jawaban {{ strtoupper($option) }}
                                                                    </a>
                                                                </li>
                                                            @endforeach

                                                            <li class="nav-item">
                                                                <a href="#review" class="nav-link" data-bs-toggle="tab">
                                                                    Kunci Jawaban & Review Pembahasan
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

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
                                                                        value="{{ $soal ? Crypt::encrypt($soal->id) : '' }}" />
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
                                                                    name="klasifikasi" id="Klasifikasi" required
                                                                    onchange="changeClassification(this)">
                                                                    <option value="">
                                                                        -- Pilih Klasifikasi --
                                                                    </option>
                                                                    @foreach ($klasifikasi_soal as $klasifikasi)
                                                                        <option
                                                                            {{ strval(old('klasifikasi', $soal?->klasifikasi_soal_id)) === strval($klasifikasi->id) ? 'selected' : '' }}
                                                                            value="{{ $klasifikasi->id }}">
                                                                            {{ $klasifikasi->judul }}
                                                                            ({{ $klasifikasi->alias }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('klasifikasi')
                                                                    <small class="text-danger">
                                                                        * {{ $message }}
                                                                    </small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="berbobot">
                                                                    Soal Berbobot <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="hidden" name="berbobot"
                                                                    class="form-control" />
                                                                <select name="berbobot-select" class="form-control"
                                                                    id="berbobot" onchange="changeSoalBerbobot(this)"
                                                                    required disabled>
                                                                    @foreach ([0, 1] as $option)
                                                                        <option value="{{ $option }}"
                                                                            {{ strval(old('berbobot', $soal?->berbobot)) === strval($option) ? 'selected' : '' }}>
                                                                            {{ $option === 1 ? 'Ya' : 'Tidak' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('berbobot')
                                                                    <small class="text-danger">
                                                                        * {{ $message }}
                                                                    </small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="Soal">
                                                                    Soal Pertanyaan <span class="text-danger">*</span>
                                                                </label>
                                                                <textarea required id="Soal" class="contentSoal" name="soal">{{ old('soal', $soal?->soal ?? '') }}</textarea>
                                                                @error('soal')
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
                                                                    data-default-file= "{{ $soal ? asset('storage/soal/' . $soal->gambar) : '' }}"
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
                                                        @php
                                                            $soalArray = [];
                                                            if ($soal) {
                                                                $soalArray = $soal->toArray();
                                                            }
                                                        @endphp
                                                        @foreach ($options as $option)
                                                            <div class="tab-pane" id="tab-jawaban-{{ $option }}">
                                                                <div class="form-group">
                                                                    <label for="poin_a">Poin {{ strtoupper($option) }}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="number" id="poin_{{ $option }}"
                                                                        name="poin_{{ $option }}"
                                                                        value="{{ old('poin_' . $option, @$soalArray['poin_' . $option] ?? 0) }}"
                                                                        class="form-control" autocomplete="off"
                                                                        min="0" required />
                                                                    @error('poin_' . $option)
                                                                        <small class="text-danger">
                                                                            * {{ $message }}
                                                                        </small>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="form_jawaban_{{ $option }}">
                                                                        Jawaban {{ strtoupper($option) }}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <textarea class="contentJawaban{{ strtoupper($option) }}" id="form_jawaban_{{ $option }}"
                                                                        name="jawaban_{{ $option }}" required>{{ old('jawaban_' . $option, @$soalArray['jawaban_' . $option] ?? '') }}</textarea>
                                                                    @error('jawaban_' . $option)
                                                                        <small class="text-danger">
                                                                            * {{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="tab-pane" id="review">
                                                            <div class="form-group" id="form-kunci-jawaban">
                                                                <label for="Kunci">
                                                                    Kunci Jawaban <span class="text-danger">*</span>
                                                                </label>
                                                                <select name="kunciJawaban" class="form-control"
                                                                    id="Kunci">
                                                                    <option value="">
                                                                        -- Pilih Kunci Jawaban --
                                                                    </option>
                                                                    @foreach ($options as $option)
                                                                        <option value="{{ strtoupper($option) }}"
                                                                            {{ strtoupper(old('kunciJawaban', $soal?->kunci_jawaban)) === strtoupper($option) ? 'selected' : '' }}>
                                                                            {{ strtoupper($option) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('kunciJawaban')
                                                                    <small class="text-danger">
                                                                        * {{ $message }}
                                                                    </small>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="ReviewPembahasan">Review Pembahasan <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea class="contentReviewPembahasan" id="ReviewPembahasan" required name="reviewPembahasan">{{ $soal ? $soal->review_pembahasan : old('reviewPembahasan') }}</textarea>
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
@section('scripts')
    <script>
        let checkClearEmptyForm = false;
        const defaultConfigRichText = {
            code: false,
            placeholder: ''
        };

        const classifications = <?= json_encode($klasifikasi_soal) ?>;

        const richTextElements = [
            '.contentSoal',
            '.contentJawabanA',
            '.contentJawabanB',
            '.contentJawabanC',
            '.contentJawabanD',
            '.contentJawabanE',
            '.contentReviewPembahasan',
        ];

        $(document).ready(function() {
            changeClassification($('[name="klasifikasi"]'));
            changeSoalBerbobot($('[name="berbobot-select"]'));

            $('#save-question').submit(function(e) {
                if (!checkClearEmptyForm) {
                    e.preventDefault();

                    for (let richTextElement of richTextElements) {
                        if ($(richTextElement).val().trim() == '<div><br></div>') {
                            $(richTextElement).val('');
                        }
                    }

                    checkClearEmptyForm = true;
                    $('#save-question').submit();
                } else {}
            })


            for (let richTextElement of richTextElements) {
                $(richTextElement).richText(defaultConfigRichText);
            }
        });

        function changeSoalBerbobot(e) {
            const value = $(e).val();
            $('[name="berbobot"]').val(value);

            if (value === '1') {
                $('#form-kunci-jawaban').slideUp()
            } else {
                $('#form-kunci-jawaban').slideDown()
            }
        }

        function changeClassification(e) {
            const value = $(e).val();
            const find = classifications.find(e => String(e.id) === String(value))
            if (!find) {
                $('[name="berbobot-select"]').attr('disabled', false);
                return;
            }

            $('[name="berbobot-select"]').attr('disabled', true);

            $('[name="berbobot-select"]').val(String(find.berbobot));
            $('[name="berbobot"]').val(String(find.berbobot));

            if (String(find.berbobot) === '1') {
                $('#form-kunci-jawaban').slideUp()
            } else {
                $('#form-kunci-jawaban').slideDown()
            }
        }
    </script>
@endsection
