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
                <div class="row flex-column-reverse flex-lg-row sidemenu-height">
                    <div class="col-lg-8">
                        <div class="card custom-card">
                            <div class="card-body">
                                @foreach ($examResultPassinGrade as $slideGraphScore)
                                    <div class="d-flex flex-column-reverse flex-lg-row align-items-center">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <div class="text-center fs-5">
                                                            {{ $slideGraphScore->alias }}
                                                        </div>
                                                    </th>
                                                </tr>
                                                @if ($slideGraphScore->benar === null && $slideGraphScore->salah === null)
                                                    <tr>
                                                        <td>Dijawab</td>
                                                        <td>{{ $slideGraphScore->terjawab }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>Benar</td>
                                                        <td>{{ $slideGraphScore->benar }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Salah</td>
                                                        <td>{{ $slideGraphScore->salah }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td>Dilewati</td>
                                                    <td>{{ $slideGraphScore->terlewati }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Nilai</td>
                                                    <td>{{ $slideGraphScore->total_nilai }}</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <canvas id="chartEvaluation-{{ $slideGraphScore->id }}"></canvas>
                                    </div>
                                @endforeach
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
                                                    src="{{ asset('storage/' . $review->gambar) }}"
                                                    data-bs-target="#modalImg-id-{{ $review->id }}"
                                                    data-bs-toggle="modal" alt="Soal ID: {{ $review->id }}"
                                                    title="Soal ID: {{ $review->id }}" loading="lazy" />
                                                <!-- Preview modal -->
                                                <div class="modal fade" id="modalImg-id-{{ $review->id }}">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content modal-content-demo">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title"><i class="fa fa-book"></i>
                                                                    Gambar Soal
                                                                </h6><button aria-label="Close" class="btn-close"
                                                                    data-bs-dismiss="modal" type="button"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="{{ asset('storage/' . $review->gambar) }}"
                                                                    alt="Soal ID: {{ $review->id }}"
                                                                    title="Soal ID: {{ $review->id }}" loading="lazy" />
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
                                                @php
                                                    $options = [
                                                        'a' => [
                                                            'content' => $review->jawaban_a,
                                                            'poin' => $review->poin_a,
                                                        ],
                                                        'b' => [
                                                            'content' => $review->jawaban_b,
                                                            'poin' => $review->poin_b,
                                                        ],
                                                        'c' => [
                                                            'content' => $review->jawaban_c,
                                                            'poin' => $review->poin_c,
                                                        ],
                                                        'd' => [
                                                            'content' => $review->jawaban_d,
                                                            'poin' => $review->poin_d,
                                                        ],
                                                        'e' => [
                                                            'content' => $review->jawaban_e,
                                                            'poin' => $review->poin_e,
                                                        ],
                                                    ];
                                                @endphp
                                                @foreach ($options as $key => $option)
                                                    <div class="d-flex align-items-start gap-2">
                                                        <div class="d-flex align-items-start gap-1">
                                                            <span>
                                                                {{ $key }}.
                                                            </span>
                                                            <div>
                                                                {!! $option['content'] !!}
                                                            </div>
                                                        </div>
                                                        @if ($review->berbobot == '1')
                                                            <span class="badge bg-warning"
                                                                title="Poin {{ $option['poin'] }}">{{ $option['poin'] }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
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
                                                    @if ($review->berbobot != '1')
                                                        <strong>Jawaban Benar : </strong> {{ $review->kunci_jawaban }}
                                                        <br>
                                                    @endif
                                                    {!! $review->review_pembahasan !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @php
                                        $no++;
                                    @endphp
                                @endforeach

                                @if ($unAnsweredQuestions->count() > 0)
                                    <h4 class="text-center">Soal Terlewati</h4>

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
                                                        src="{{ asset('storage/' . $review->gambar) }}"
                                                        data-bs-target="#modalImg-id-{{ $review->id }}"
                                                        data-bs-toggle="modal" alt="Soal ID: {{ $review->id }}"
                                                        title="Soal ID: {{ $review->id }}" loading="lazy" />
                                                    <!-- Preview modal -->
                                                    <div class="modal fade" id="modalImg-id-{{ $review->id }}">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content modal-content-demo">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title"><i class="fa fa-book"></i>
                                                                        Gambar Soal
                                                                    </h6><button aria-label="Close" class="btn-close"
                                                                        data-bs-dismiss="modal" type="button"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{ asset('storage/' . $review->gambar) }}"
                                                                        alt="Soal ID: {{ $review->id }}"
                                                                        title="Soal ID: {{ $review->id }}"
                                                                        loading="lazy" />
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
                                                    @php
                                                        $options = [
                                                            'a' => [
                                                                'content' => $review->jawaban_a,
                                                                'poin' => $review->poin_a,
                                                            ],
                                                            'b' => [
                                                                'content' => $review->jawaban_b,
                                                                'poin' => $review->poin_b,
                                                            ],
                                                            'c' => [
                                                                'content' => $review->jawaban_c,
                                                                'poin' => $review->poin_c,
                                                            ],
                                                            'd' => [
                                                                'content' => $review->jawaban_d,
                                                                'poin' => $review->poin_d,
                                                            ],
                                                            'e' => [
                                                                'content' => $review->jawaban_e,
                                                                'poin' => $review->poin_e,
                                                            ],
                                                        ];
                                                    @endphp
                                                    @foreach ($options as $key => $option)
                                                        <div class="d-flex align-items-start gap-2">
                                                            <div class="d-flex align-items-start gap-1">
                                                                <span>
                                                                    {{ $key }}.
                                                                </span>
                                                                <div>
                                                                    {!! $option['content'] !!}
                                                                </div>
                                                            </div>
                                                            @if ($review->berbobot == '1')
                                                                <span class="badge bg-warning"
                                                                    title="Poin {{ $option['poin'] }}">{{ $option['poin'] }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row mt-1">
                                                <div class="col-md-12">
                                                    <h5>Review Pembahasan</h5>
                                                    <div class="alert alert-success" role="alert">
                                                        @if ($review->berbobot != '1')
                                                            <strong>Jawaban Benar : </strong> {{ $review->kunci_jawaban }}
                                                            <br>
                                                        @endif
                                                        {!! $review->review_pembahasan !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card custom-card text-center">
                            <div class="row row-sm">
                                <div class="col-xl-6 col-lg-6 col-sm-12 pe-0 ps-0 border-end">
                                    <div class="card-body text-center">
                                        <h6 class="mb-0">Terjawab</h6>
                                        <h2 class="mb-1 mt-2 number-font"><span
                                                class="counter badge bg-info">{{ $exam->hasil->terjawab }}</span>
                                        </h2>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-sm-12 pe-0 ps-0">
                                    <div class="card-body text-center">
                                        <h6 class="mb-0">Tidak Terjawab</h6>
                                        <h2 class="mb-1 mt-2 number-font"><span
                                                class="counter badge bg-warning">{{ $exam->hasil->tidak_terjawab }}</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Graph Total Score Per Classification --}}
                        <div class="card custom-card text-center">
                            <div class="card-body">
                                <div>
                                    <h6 class="main-content-label mb-1">Grafik Nilai</h6>
                                    <p class="text-muted  card-sub-title">Berdasarkan Klasifikasi</p>
                                </div>
                                <div class="chartjs-wrapper-demo">
                                    <canvas id="chartTotalScorePerClassification"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>
@endsection

@section('styles')

@endsection

@section('scripts')
    <script>
        const examResultPassinGrade = <?= json_encode($examResultPassinGrade) ?>;

        $(function() {

            for (let classification of examResultPassinGrade) {
                const labels = [];
                const datasetData = [];
                const datasetBackgroundColor = [];
                if (classification.benar === null && classification.salah === null) {
                    labels.push('Dijawab', 'Dilewati')
                    datasetData.push(classification.terjawab, classification.terlewati);
                    datasetBackgroundColor.push('rgb(54, 162, 235)', 'rgb(255, 205, 86)');
                } else {
                    labels.push('Benar', 'Salah', 'Dilewati')
                    datasetData.push(classification.benar, classification.salah, classification.terlewati);
                    datasetBackgroundColor.push('rgb(54, 162, 235)', 'rgb(255, 99, 132)', 'rgb(255, 205, 86)');
                }

                new Chart(document.getElementById(`chartEvaluation-${classification.id}`).getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'My First Dataset',
                            data: datasetData,
                            backgroundColor: datasetBackgroundColor,
                            hoverOffset: 4
                        }]
                    },
                });
            }

            // Bar-Score Per Classification
            const labelsChartTotalScorePerClassification = []
            const passingGradeData = []
            const userScoreData = []
            for (let classification of examResultPassinGrade) {
                labelsChartTotalScorePerClassification.push(classification.alias);
                passingGradeData.push(classification.passing_grade);
                userScoreData.push(classification.total_nilai);
            }

            const chartTotalScorePerClassification = new Chart(document.getElementById(
                "chartTotalScorePerClassification").getContext('2d'), {
                type: 'line',
                data: {
                    labels: labelsChartTotalScorePerClassification,
                    datasets: [{
                            label: "Passing Grade",
                            data: passingGradeData,
                            borderWidth: 2,
                            borderColor: '#19B159',
                            backgroundColor: '#19B159',
                            fill: false,
                            borderWidth: 2.0,
                            pointBackgroundColor: '#ffffff',
                        },
                        {
                            label: "Nilai Anda",
                            data: userScoreData,
                            borderWidth: 2,
                            borderColor: '#ff9b21',
                            backgroundColor: '#ff9b21',
                            fill: false,
                            borderWidth: 2.0,
                            pointBackgroundColor: '#ffffff',
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 20,
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
                        display: true,
                        labels: {
                            fontColor: "#77778e"
                        },
                    },
                }
            });
        });
    </script>
@endsection
