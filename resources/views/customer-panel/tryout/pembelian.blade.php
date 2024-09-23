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

                <!-- Row -->
                <div class="row sidemenu-height">
                    <div class="table-responsive">
                        <table class="table table-bordered border-bottom">
                            <thead>
                                <tr>
                                    <td id="no">No</td>
                                    <th id="action">Aksi</th>
                                    <th id="transaction">ID Transaksi</th>
                                    <th id="email">Produk</th>
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
                                                <button onclick="showSnap('{{ $row->snap_token }}')" id="pay-button"
                                                    class="mt-3 btn btn-block btn-warning text-nowrap">
                                                    Bayar Sekarang <i class="mdi mdi-arrow-right"></i>
                                                </button>
                                            @endif

                                            @if ($row->status_order === 'paid')
                                                <form
                                                    action="{{ route('ujian.main', ['id' => Crypt::encrypt($row->id), 'param' => Crypt::encrypt('berbayar')]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        class="btn btn-pills btn-primary d-block d-md-inline-block text-nowrap">
                                                        Mulai Ujian <i class="mdi mdi-arrow-right"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->transaction_id }}
                                        </td>
                                        <td>
                                            {{ $row->nama_tryout }}
                                        </td>
                                        <td>
                                            <span class="badge"
                                                style="background: {{ $statusCheck['bg-color'] }}; color: {{ $statusCheck['color'] }}">
                                                {{ $statusCheck['title'] }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ Number::currency($row->total, in: 'IDR') }}
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
