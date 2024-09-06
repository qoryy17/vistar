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
                            <li class="breadcrumb-item"><a href="#">Profil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">

                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                {{-- Filter pembelian produk --}}
                <form action="{{ route('site.search-pembelian') }}" method="GET">
                    {{-- @csrf
                    @method('POST') --}}
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select name="kategori" class="form-control selectProduk" id="kategori">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="CPNS">CPNS (Calon Pegawai Negeri Sipil)</option>
                                    <option value="PPK">PPPK (Pegawai Pemerintah Dengan Perjanjian Kerja)</option>
                                    <option value="Kedinasan">Kedinasan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tahun">Tahun </label>
                                <select name="tahun" id="tahun" class="form-control selectTahun">
                                    <option value="">-- Pilih Tahun --</option>
                                    @for ($tahun = 2024; $tahun <= 2026; $tahun++)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="Filter">Filter </label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-block btn-default btn-web"><i class="fa fas fa-search"></i>
                                            Filter</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('site.pembelian') }}"
                                            class="btn btn-block btn-default btn-web1"><i class="fa fas fa-refresh"></i>
                                            Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Row -->
                <div class="row sidemenu-height">
                    @foreach ($search as $pembelian)
                        @php
                            $statusCheck = null;
                            if (array_key_exists($pembelian->status_order, $transactionStatusList)) {
                                $statusCheck = $transactionStatusList[$pembelian->status_order];
                            }
                        @endphp
                        <div class="col-lg-6">
                            {{-- Informasi Paket Tryout --}}
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div>
                                        <h6>
                                            Informasi Paket
                                        </h6>

                                        {{--  Show Transaction Status  --}}
                                        @if ($statusCheck)
                                            <span class="badge"
                                                style="background: {{ $statusCheck['bg-color'] }}; color: {{ $statusCheck['color'] }}">{{ $statusCheck['title'] }}</span>
                                        @endif

                                        {{--  Show Transaction Status  --}}
                                        <h6 class="mb-2 text-primary">
                                            <span class="fs-25 me-2">{{ $pembelian->nama_tryout }}</span><br>
                                            <span class="pt-4 text-muted fw-normal">{{ $pembelian->keterangan }}</span>
                                        </h6>
                                        <span class="text-muted tx-12">
                                            Waktu Pembelian : {{ $pembelian->created_at }}
                                        </span>
                                        {{--  Note: This is shouldn't be here
                                            It should be another page to show transaction status.
                                        --}}
                                        @if ($pembelian->status_order === 'pending' && $pembelian->snap_token)
                                            {{--  Please change the design to fit the template  --}}
                                            <button onclick="showSnap('{{ $pembelian->snap_token }}')" id="pay-button"
                                                class="mt-2 btn btn-block btn-primary">
                                                Bayar Sekarang <i class="mdi mdi-arrow-right"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>

    <script src="{{ url('resources/web/dist/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script type="text/javascript">
        function showSnap(snap_token) {
            snap.pay(snap_token, {
                onSuccess: function(result) {
                    window.location.href = "{{ route('site.pembelian') }}";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('site.pembelian') }}";

                },
                onError: function(result) {
                    window.location.href = "{{ route('site.pembelian') }}";
                }

            });
        };
    </script>
@endsection
