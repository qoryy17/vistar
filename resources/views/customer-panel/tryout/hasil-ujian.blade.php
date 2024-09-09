@extends('customer-panel.layout.main')
@section('title', $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">Hasil Ujian</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Ujian</a></li>
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
                    <div class="col-lg-9">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="row row-sm">
                                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0 border-end">
                                        <div class="card-body text-center">
                                            <h6 class="mb-0">Benar</h6>
                                            <h2 class="mb-1 mt-2 number-font"><span
                                                    class="counter badge bg-success">{{ $exam->hasil->benar }}</span>
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0 border-end">
                                        <div class="card-body text-center">
                                            <h6 class="mb-0">Salah</h6>
                                            <h2 class="mb-1 mt-2 number-font"><span
                                                    class="counter badge bg-danger">{{ $exam->hasil->salah }}</span></h2>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0 border-end">
                                        <div class="card-body text-center">
                                            <h6 class="mb-0">Terjawab</h6>
                                            <h2 class="mb-1 mt-2 number-font"><span
                                                    class="counter badge bg-info">{{ $exam->hasil->terjawab }}</span>
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-sm-6 pe-0 ps-0">
                                        <div class="card-body text-center">
                                            <h6 class="mb-0">Tidak Terjawab</h6>
                                            <h2 class="mb-1 mt-2 number-font"><span
                                                    class="counter badge bg-warning">{{ $exam->hasil->tidak_terjawab }}</span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card custom-card">
                            <div class="card-body">
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($reviewJawaban as $review)
                                    <div>
                                        <h5>Soal No. {{ $no }}</h5>
                                        <div style="font-size: 14px;">
                                            <p>
                                                {!! $review->soal !!}
                                            </p>
                                            @if (!empty($review->gambar))
                                                <img height="300px" width="img img-thumbnail"
                                                    src="{{ asset('storage/soal/' . $review->gambar) }}" alt="gambar"
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
                                                                <img src="{{ asset('soal/' . $review->gambar) }}"
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
                                            <div class="mt-2">
                                                <p>
                                                    <span>a. {{ strip_tags($review->jawaban_a) }}</span>
                                                </p>
                                                <p>
                                                    <span>b. {{ strip_tags($review->jawaban_b) }}</span>
                                                </p>
                                                <p>
                                                    <span>c. {{ strip_tags($review->jawaban_c) }}</span>
                                                </p>
                                                <p>
                                                    <span>d. {{ strip_tags($review->jawaban_d) }}</span>
                                                </p>
                                                <p>
                                                    <span>e. {{ strip_tags($review->jawaban_e) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <h5>Review Pembahasan</h5>
                                                <div class="alert  @if ($review->jawaban != $review->kunci_jawaban) alert-danger @else alert-success @endif"
                                                    role="alert">
                                                    <strong>Jawaban Anda : </strong> {{ $review->jawaban }}

                                                </div>
                                                <div class="alert alert-success" role="alert">
                                                    <strong>Jawaban Benar : </strong> {{ $review->kunci_jawaban }}
                                                    <br>
                                                    {{ strip_tags($review->review_pembahasan) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach

                                <hr />

                                <h5>Soal Terlewati</h5>

                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($unAnsweredQuestions as $review)
                                    <div>
                                        <h5>Soal No. {{ $no }}</h5>
                                        <div style="font-size: 14px;">
                                            <p>
                                                {!! $review->soal !!}
                                            </p>
                                            @if (!empty($review->gambar))
                                                <img height="300px" width="img img-thumbnail"
                                                    src="{{ asset('storage/soal/' . $review->gambar) }}" alt="gambar"
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
                                                                <img src="{{ asset('soal/' . $review->gambar) }}"
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
                                            <div class="mt-2">
                                                <p>
                                                    <span>a. {{ strip_tags($review->jawaban_a) }}</span>
                                                </p>
                                                <p>
                                                    <span>b. {{ strip_tags($review->jawaban_b) }}</span>
                                                </p>
                                                <p>
                                                    <span>c. {{ strip_tags($review->jawaban_c) }}</span>
                                                </p>
                                                <p>
                                                    <span>d. {{ strip_tags($review->jawaban_d) }}</span>
                                                </p>
                                                <p>
                                                    <span>e. {{ strip_tags($review->jawaban_e) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <h5>Review Pembahasan</h5>
                                                <div class="alert alert-success" role="alert">
                                                    <strong>Jawaban Benar : </strong> {{ $review->kunci_jawaban }}
                                                    <br>
                                                    {{ strip_tags($review->review_pembahasan) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        {{-- Grafik review pembahasan --}}
                        <div class="card custom-card text-center">
                            <div class="card-body">
                                <div>
                                    <h6 class="main-content-label mb-1">Grafik Review</h6>
                                    <p class="text-muted  card-sub-title">Ujian Tryout</p>
                                </div>
                                <div class="chartjs-wrapper-demo">
                                    <canvas id="chartEvaluasi"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>
    <!-- Jquery js-->
    <script src="{{ url('resources/spruha/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(function() {
            // Bar-Evaluasi
            var context = document.getElementById("chartEvaluasi").getContext('2d');
            var myChartEvaluasi = new Chart(context, {
                type: 'bar',
                data: {
                    labels: ["Evaluasi"],
                    datasets: [{
                        label: 'Benar',
                        data: [{{ $exam->hasil->benar }}],
                        borderWidth: 2,
                        backgroundColor: '#19B159',
                        borderColor: '#19B159',
                        borderWidth: 2.0,
                        pointBackgroundColor: '#ffffff',

                    }, {
                        label: 'Salah',
                        data: [{{ $exam->hasil->salah }}],
                        borderWidth: 2,
                        backgroundColor: '#F16D75',
                        borderColor: '#F16D75',
                        borderWidth: 2.0,
                        pointBackgroundColor: '#ffffff',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 100,
                                fontColor: "#77778e",
                            },
                            gridLines: {
                                color: 'rgba(119, 119, 142, 0.2)'
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                display: true,
                                fontColor: "#77778e",
                            },
                            gridLines: {
                                display: false,
                                color: 'rgba(119, 119, 142, 0.2)'
                            }
                        }]
                    },
                    legend: {
                        labels: {
                            fontColor: "#77778e"
                        },
                    },
                }
            });
        });
    </script>
@endsection
