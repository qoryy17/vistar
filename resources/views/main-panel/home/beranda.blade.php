@extends('main-panel.layout.main')
@section('title', 'Vistar Indonesia | Panel Utama')
@section('content')
    <div class="main-content pt-0 hor-content">

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">Selamat Malam, {{ $page_title }}</h2>
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
                    <div class="col-lg-8">

                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="card-order">
                                    <label class="main-content-label mb-3 pt-1">Total Profit</label>
                                    <h2 class="text-end"><i style="color: #0075B8;"
                                            class="icon-size mdi mdi-poll-box float-start"></i>
                                        <span class="font-weight-bold">{{ Number::currency($sumTryout, in: 'IDR') }}</span>
                                    </h2>
                                    <p class="mb-0 mt-4 text-muted">Profit Hari Ini<span
                                            class="float-end">{{ Number::currency($sumTryoutPerhari, in: 'IDR') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card overflow-hidden">
                            <div class="card-body">
                                <div class="mb-2">
                                    <h6 class="main-content-label mb-1">Total Penjualan</h6>
                                    <p class="text-muted  card-sub-title">Tahun 2024</p>
                                    <select name="" id="yearSelect" class="form-control">
                                        <option value="">Pilih Tahun</option>
                                        @for ($tahun = 2024; $tahun <= 2026; $tahun++)
                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="chartjs-wrapper-demo">
                                    <canvas id="chartBar1"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        {{-- Statistik Produk Penjualan Tryout --}}
                        <div class="card custom-card">
                            <div class="card-header  border-bottom-0 pb-0">
                                <div>
                                    <div class="d-flex">
                                        <label class="main-content-label my-auto pt-2">Statistik Produk</label>
                                    </div>
                                    <span class="d-block tx-12 mt-2 mb-0 text-muted"> Penjualan Produk Tryout Bulan Ini
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mt-1">
                                    <div class="col-6">
                                        <span class="">Tryout : CPNS</span>
                                    </div>
                                    <div class="col-3 my-auto">
                                        <div class="progress ht-6 my-auto">
                                            <div style="background-color: #0075B8;"
                                                class="progress-bar ht-6 wd-{{ $countStatistikCPNS->count() }}p"
                                                role="progressbar" aria-valuenow="{{ $countStatistikCPNS->count() }}"
                                                aria-valuemin="0" aria-valuemax="1000">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex">
                                            <span class="tx-13"><b>{{ $countStatistikCPNS->count() }}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <span class="">Tryout : PP3K</span>
                                    </div>
                                    <div class="col-3 my-auto">
                                        <div class="progress ht-6 my-auto">
                                            <div style="background-color: #0075B8;"
                                                class="progress-bar ht-6 wd-{{ $countStatistikPPPK->count() }}p"
                                                role="progressbar" aria-valuenow="{{ $countStatistikPPPK->count() }}"
                                                aria-valuemin="0" aria-valuemax="1000">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex">
                                            <span class="tx-13"><b>{{ $countStatistikPPPK->count() }}</b></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <span class="">Tryout : Kedinasan</span>
                                    </div>
                                    <div class="col-3 my-auto">
                                        <div class="progress ht-6 my-auto">
                                            <div style="background-color: #0075B8;"
                                                class="progress-bar ht-6 wd-$countStatistikKedinasan->count() }}p"
                                                role="progressbar" aria-valuenow="{{ $countStatistikKedinasan->count() }}"
                                                aria-valuemin="0" aria-valuemax="1000">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex">
                                            <span class="tx-13"><b>{{ $countStatistikKedinasan->count() }}</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="card-order ">
                                    <label class="main-content-label mb-3 pt-1">Customer Terdaftar</label>
                                    <h2 class="text-end card-item-icon card-icon">
                                        <i style="color: #0075B8;"
                                            class="mdi mdi-account-multiple icon-size float-start"></i>
                                        <span class="font-weight-bold">{{ $countCustomer }}</span>
                                    </h2>
                                    <p class="mb-0 mt-4 text-muted">Customer Hari Ini<span
                                            class="float-end">{{ $countCustomerPerhari }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="card-order">
                                    <label class="main-content-label mb-3 pt-1">Total Tryout Terjual</label>
                                    <h2 class="text-end"><i style="color: #0075B8;"
                                            class="icon-size mdi mdi-airplay float-start"></i>
                                        <span class="font-weight-bold">{{ $countTryout }}</span>
                                    </h2>
                                    <p class="mb-0 mt-4 text-muted">Tryout Terjual Hari Ini<span
                                            class="float-end">{{ $countTryoutPerhari }}</span></p>
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
    <!-- Internal Chartjs charts js-->
    <script src="{{ url('resources/spruha/assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script>
        function loadChart(data) {
            var ctx = document.getElementById("chartBar1").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: 'CPNS',
                        data: [
                            data.CPNS[1] || 0,
                            data.CPNS[2] || 0,
                            data.CPNS[3] || 10,
                            data.CPNS[4] || 0,
                            data.CPNS[5] || 0,
                            data.CPNS[6] || 0,
                            data.CPNS[7] || 0,
                            data.CPNS[8] || 0,
                            data.CPNS[9] || 0,
                            data.CPNS[10] || 0,
                            data.CPNS[11] || 0,
                            data.CPNS[12] || 0
                        ],
                        borderWidth: 2,
                        backgroundColor: '#0075B8',
                        borderColor: '#0075B8',
                        pointBackgroundColor: '#ffffff',
                    }, {
                        label: 'PPK',
                        data: [
                            data.PPK[1] || 0,
                            data.PPK[2] || 0,
                            data.PPK[3] || 0,
                            data.PPK[4] || 0,
                            data.PPK[5] || 0,
                            data.PPK[6] || 0,
                            data.PPK[7] || 0,
                            data.PPK[8] || 0,
                            data.PPK[9] || 0,
                            data.PPK[10] || 0,
                            data.PPK[11] || 0,
                            data.PPK[12] || 0
                        ],
                        borderWidth: 2,
                        backgroundColor: '#F8AA3B',
                        borderColor: '#F8AA3B',
                        pointBackgroundColor: '#ffffff',
                    }, {
                        label: 'Kedinasan',
                        data: [
                            data.Kedinasan[1] || 0,
                            data.Kedinasan[2] || 0,
                            data.Kedinasan[3] || 0,
                            data.Kedinasan[4] || 0,
                            data.Kedinasan[5] || 0,
                            data.Kedinasan[6] || 0,
                            data.Kedinasan[7] || 0,
                            data.Kedinasan[8] || 0,
                            data.Kedinasan[9] || 0,
                            data.Kedinasan[10] || 0,
                            data.Kedinasan[11] || 0,
                            data.Kedinasan[12] || 0
                        ],
                        borderWidth: 2,
                        backgroundColor: '#2ECA8B',
                        borderColor: '#2ECA8B',
                        pointBackgroundColor: '#ffffff',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 150,
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
        }


        document.getElementById('yearSelect').addEventListener('change', function() {
            var selectedYear = this.value;

            // Fetch new data via AJAX
            $.ajax({
                url: "{{ route('main.chart') }}",
                method: 'GET',
                data: {
                    year: selectedYear
                },
                success: function(data) {
                    loadChart(data);
                },
                error: function() {
                    alert('Failed to retrieve data. Please try again.');
                }
            });
        });
    </script>
@endsection
