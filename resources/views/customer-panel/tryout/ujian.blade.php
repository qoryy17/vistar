@extends('customer-panel.layout.main')
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
                            <li class="breadcrumb-item"><a href="#">Ujian</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">
                            <h6 id="timer">
                                Waktu tersisa: <span id="time"></span>
                            </h6>
                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- Row -->
                <div class="row sidemenu-height">
                    <div class="col-lg-12">
                        @if ($soalUjian->count())
                            @foreach ($soalUjian as $ujian)
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <h5 class="mb-2">Soal Pertanyaan</h5>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-block btn-sm btn-primary btn-web"
                                                        data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right">
                                                        <i class="fe fe-menu header-icons"></i>
                                                        Daftar Soal
                                                    </a>
                                                </div>
                                            </div>
                                            <div style="font-size: 15px;">
                                                <p>
                                                    {{ $soalUjian->currentPage() }}. {{ strip_tags($ujian->soal) }}
                                                </p>
                                                @if (!empty($ujian->gambar))
                                                    <img height="300px" width="img img-thumbnail"
                                                        src="{{ asset('storage/soal/' . $ujian->gambar) }}" alt="gambar"
                                                        data-bs-target="#modalImg" data-bs-toggle="modal">
                                                    <!-- Preview modal -->
                                                    <div class="modal fade" id="modalImg">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content modal-content-demo">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title"><i class="fa fa-book"></i>
                                                                        Gambar Soal
                                                                    </h6><button aria-label="Close" class="btn-close"
                                                                        data-bs-dismiss="modal" type="button"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{ asset('soal/' . $ujian->gambar) }}"
                                                                        alt="img">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-sm ripple btn-danger"
                                                                        data-bs-dismiss="modal" type="button"><i
                                                                            class="fa fa-times"></i> Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Preview modal -->
                                                @endif
                                                <form id="formJawaban">
                                                    @csrf
                                                    <input type="hidden" readonly name="ujian_id"
                                                        value="{{ Crypt::encrypt($ujianId) }}">
                                                    <input type="hidden" readonly name="soal_ujian_id"
                                                        value="{{ Crypt::encrypt($ujian->id) }}">
                                                    <input type="hidden" readonly name="kode_soal"
                                                        value="{{ Crypt::encrypt($ujian->kode_soal) }}">

                                                    <label class="rdiobox">
                                                        <input name="jawaban" type="radio" id="JawabanA" value="A"
                                                            @if (isset($jawabanTersimpan[$ujian->id])) @if ($jawabanTersimpan[$ujian->id] == 'A') checked @endif
                                                            @endif>
                                                        <span>a. {{ strip_tags($ujian->jawaban_a) }}</span>
                                                    </label>
                                                    <label class="rdiobox">
                                                        <input name="jawaban" type="radio" id="Jawaban" value="B"
                                                            @if (isset($jawabanTersimpan[$ujian->id])) @if ($jawabanTersimpan[$ujian->id] == 'B') checked @endif
                                                            @endif>
                                                        <span>b. {{ strip_tags($ujian->jawaban_b) }}</span>
                                                    </label>
                                                    <label class="rdiobox">
                                                        <input name="jawaban" type="radio" id="Jawaban" value="C"
                                                            @if (isset($jawabanTersimpan[$ujian->id])) @if ($jawabanTersimpan[$ujian->id] == 'C') checked @endif
                                                            @endif>
                                                        <span>c. {{ strip_tags($ujian->jawaban_c) }}</span>
                                                    </label>
                                                    <label class="rdiobox">
                                                        <input name="jawaban" type="radio" id="Jawaban" value="D"
                                                            @if (isset($jawabanTersimpan[$ujian->id])) @if ($jawabanTersimpan[$ujian->id] == 'D') checked @endif
                                                            @endif>
                                                        <span>d. {{ strip_tags($ujian->jawaban_d) }}</span>
                                                    </label>
                                                    <label class="rdiobox">
                                                        <input name="jawaban" type="radio" id="Jawaban" value="E"
                                                            @if (isset($jawabanTersimpan[$ujian->id])) @if ($jawabanTersimpan[$ujian->id] == 'E') checked @endif
                                                            @endif>
                                                        <span>e. {{ strip_tags($ujian->jawaban_e) }}</span>
                                                    </label>

                                                    @error('jawaban')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </form>
                                            </div>
                                            <!-- Tombol Navigasi -->
                                            <div class="row mt-4">
                                                <div class="col mt-2">
                                                    @if (!$soalUjian->onFirstPage())
                                                        <button
                                                            onclick="simpanJawaban('{{ $soalUjian->previousPageUrl() }}')"
                                                            class="btn btn-default btn-web1 btn-block">
                                                            <i class="fa fa-angle-left"></i> Sebelumnya
                                                        </button>
                                                    @else
                                                        <button class="btn btn-default btn-web1 btn-block" disabled>
                                                            <i class="fa fa-angle-left"></i> Sebelumnya
                                                        </button>
                                                    @endif

                                                </div>
                                                @if ($soalUjian->hasMorePages())
                                                    <div class="col mt-2">
                                                        <button onclick="simpanJawaban('{{ $soalUjian->nextPageUrl() }}')"
                                                            class="btn btn-default btn-web btn-block">
                                                            Selanjutnya <i class="fa fa-angle-right"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="col mt-2">
                                                        <button
                                                            onclick='swal({
                                                                    title: "Selesaikan Ujian",
                                                                    text: "Apakah anda ingin menyelesaikan ujian sekarang ?",
                                                                    type: "warning",
                                                                    showCancelButton: true,
                                                                    closeOnConfirm: false,
                                                                    confirmButtonText: "Ya",
                                                                    cancelButtonText: "Batal",
                                                                    showLoaderOnConfirm: true }, function () 
                                                                        { 
                                                                        setTimeout(function(){  
                                                                            window.location.href = "{{ route('ujian.simpan-hasil', ['id' => Crypt::encrypt($ujianId), 'kode_soal' => Crypt::encrypt($ujian->kode_soal), 'param' => $param]) }}";
                                                                    }, 1000); });'
                                                            type="submit" class="btn btn-success btn-block">
                                                            <i class="fa fa-check-circle"></i> Selesai
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Total soal terjawab dan belum terjawab-->
                                        <div class="row mt-4 text-normal">
                                            <div class="col-md-6">
                                                <h6>Terjawab :
                                                    @if (!empty($jawabSoal->soal_terjawab))
                                                        <span class="badge bg-success">
                                                            {{ $jawabSoal->soal_terjawab }}
                                                        </span>
                                                        Soal
                                                    @endif
                                                </h6>
                                            </div>
                                            <div class="col-md-6" style="text-align: right">
                                                <h6>Belum Terjawab :
                                                    @if (!empty($jawabSoal->soal_belum_terjawab))
                                                        <span class="badge bg-danger">
                                                            {{ $jawabSoal->soal_belum_terjawab }}
                                                        </span>
                                                        Soal
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <div class="sidebar sidebar-right sidebar-animate">
        <div class="sidebar-icon">
            <a href="#" class="text-end float-end text-dark fs-20" data-bs-toggle="sidebar-right"
                data-bs-target=".sidebar-right"><i class="fe fe-x"></i></a>
        </div>
        <div class="sidebar-body">
            <h5>Daftar Soal</h5>
            <div class="d-flex p-2">
                <div class="box-soal-container">
                    @for ($i = 1; $i <= $totalSoal; $i++)
                        <a href="{{ route('ujian.progress', ['id' => Crypt::encrypt($ujianId), 'param' => $param, 'page' => $i]) }}"
                            class="box-soal-web"
                            @if ($soalUjian->currentPage() == $i) style="background-color : #0075B8; border-color: #0075B8; color: white;" @endif>
                            {{ $i }}
                        </a>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->
    <!-- Jquery js-->
    <script src="{{ url('resources/spruha/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        // Mengambil waktu sekarang dan waktu selesai ujian dari PHP
        const endTime = new Date('{{ $endTime }}').getTime();
        const now = new Date().getTime();

        // Update timer setiap 1 detik
        const countdown = setInterval(() => {
            const currentTime = new Date().getTime();
            const distance = endTime - currentTime;

            // Menghitung menit dan detik
            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Tampilkan hasil di elemen dengan id="time"
            document.getElementById('time').textContent = `${hours} jam ${minutes} menit ${seconds} detik`;

            // Jika waktu habis, tampilkan pesan dan hentikan timer
            if (distance < 0) {
                clearInterval(countdown);
                document.getElementById('timer').textContent = 'Waktu ujian telah habis !';
                swal({
                    title: "Notifikasi",
                    text: "Waktu ujian telah habis !",
                    type: "error"
                }, function() {
                    setTimeout(function() {
                        window.location.href =
                            "{{ route('ujian.simpan-hasil', ['id' => Crypt::encrypt($ujianId), 'kode_soal' => Crypt::encrypt($ujian->kode_soal), 'param' => $param]) }}";
                    }, 100);
                });

            }
        }, 1000);

        $(document).ready(function() {
            // Event listener untuk menyimpan jawaban saat opsi radio berubah
            $("input[name='jawaban']").change(function() {
                simpanJawabanOtomoatis();
            });
        });

        function simpanJawabanOtomoatis() {
            var formData = $('#formJawaban').serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('ujian.simpan-jawaban') }}",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        console.log('Jawaban tersimpan secara otomatis');
                    } else {
                        alert('Gagal menyimpan jawaban, silakan coba lagi.');
                    }
                },
                error: function(response) {
                    alert('Terjadi kesalahan, silakan coba lagi.');
                }
            });
        }

        function simpanJawaban(redirectUrl) {
            // Cek apakah ada jawaban yang dipilih
            if (!$("input[name='jawaban']:checked").val()) {
                swal({
                    title: "Notifikasi",
                    text: "Pilih salah satu jawaban !",
                    type: "warning"
                });
                return;
            }
            var formData = $('#formJawaban').serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('ujian.simpan-jawaban') }}",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = redirectUrl;
                    } else {
                        swal({
                            title: "Notifikasi",
                            text: "Gagal menyimpan jawaban !",
                            type: "error"
                        });
                    }
                },
                error: function(response) {
                    swal({
                        title: "Notifikasi",
                        text: "Terjadi kesalahan response data !",
                        type: "error"
                    });
                }
            });
        }
    </script>
@endsection
