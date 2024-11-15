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
                            <li class="breadcrumb-item"><a href="{{ route('site.main') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">

                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                @if ($transactions->isNotEmpty())
                    <div class="card custom-card">
                        <div class="card-body">
                            <!-- Row -->
                            <div class="row sidemenu-height">
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom">
                                        <thead>
                                            <tr>
                                                <td id="no">No</td>
                                                <th id="action">Aksi</th>
                                                <th id="transaction">ID Transaksi</th>
                                                <th id="email">Pelatihan</th>
                                                <th id="profit">Status</th>
                                                <th id="name">Total</th>
                                                <th id="created_at">Waktu Transaksi</th>
                                            </tr>
                                        </thead>
                                        {{--  IDEA: Next progress using ajax load using yajra datatables or other pacakges --}}
                                        <tbody>
                                            @php
                                                $page = $transactions->currentPage();
                                                $perPage = $transactions->perPage();

                                                $no = 1 + ($page - 1) * $perPage;
                                            @endphp
                                            @foreach ($transactions as $row)
                                                @php
                                                    $statusCheck = null;
                                                    if (array_key_exists($row->status_order, $transactionStatusList)) {
                                                        $statusCheck = $transactionStatusList[$row->status_order];
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>
                                                        @if ($row->status_order === 'pending' && $row->snap_token)
                                                            {{--  Please change the design to fit the template  --}}
                                                            <button onclick="showSnap('{{ $row->snap_token }}')"
                                                                id="pay-button"
                                                                class="mt-3 btn btn-block btn-sm btn-warning text-nowrap">
                                                                Bayar Sekarang <i class="mdi mdi-arrow-right"></i>
                                                            </button>
                                                        @endif

                                                        @if ($row->status_order === 'paid')
                                                            <a href="{{ route('customer.get-sertikom', ['category' => 'pelatihan']) }}"
                                                                type="submit"
                                                                class="btn btn-primary btn-sm d-block d-md-inline-block text-nowrap">
                                                                Lihat Pelatihan <i class="mdi mdi-arrow-right"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $row->transaction_id ?? '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->produk }}
                                                    </td>
                                                    <td>
                                                        <span class="badge"
                                                            style="background: {{ $statusCheck['bg-color'] }}; color: {{ $statusCheck['color'] }}">
                                                            {{ $statusCheck['title'] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ is_numeric($row->total) ? Number::currency($row->total, in: 'IDR') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '-' }}
                                                    </td>
                                                </tr>

                                                @php
                                                    $no++;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {!! $transactions->links() !!}
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-4 d-none d-md-flex justify-content-center">
                            <img height="400px" style="max-width: 100%; object-fit: contain;" class="img img-fluid"
                                src="{{ asset('resources/images/model-9.png') }}"
                                alt="Banner Model 9 {{ config('app.name') }}"
                                title="Banner Model 9 {{ config('app.name') }}" loading="eager" />
                        </div>
                        <div class="col-md-8" style="vertical-align: middle;">
                            <div class="card custom-card">
                                <div class="card-header p-3 tx-medium my-auto ">
                                    Uppss... Belum ada Pembelian nih !
                                </div>
                                <div class="card-body">
                                    <p style="text-align: justify">
                                        Hallo <strong>"{{ Auth::user()->name }}"</strong> "Belum ambil pelatihan?
                                        Tingkatkan dan update skill Anda sekarang untuk meraih peluang lebih besar di masa
                                        depan! Jangan lewatkan kesempatan untuk belajar dan berkembang bersama kami."
                                    </p>
                                    <p style="text-align: justify">
                                        Jangan tunggu lagi, bergabunglah
                                        sekarang dan buktikan sendiri mengapa banyak peserta lain memberikan review positif
                                        atas pelatihan kami !
                                    </p>
                                    <a class="btn btn-primary btn-sm d-block d-md-inline-block"
                                        href="{{ route('mainweb.product-sertikom', ['category' => 'pelatihan']) }}">
                                        Lihat Sekarang <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script
        src="{{ !config('services.midtrans.is_production') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script type="text/javascript">
        function showSnap(snap_token) {
            snap.pay(snap_token, {
                onSuccess: function(result) {
                    window.location.href =
                        "{{ route('site.pembelian-sertikom', ['category' => 'pelatihan']) }}";
                },
                onPending: function(result) {
                    window.location.href =
                        "{{ route('site.pembelian-sertikom', ['category' => 'pelatihan']) }}";
                },
                onError: function(result) {
                    window.location.href =
                        "{{ route('site.pembelian-sertikom', ['category' => 'pelatihan']) }}";
                }
            });
        };
    </script>
@endsection
