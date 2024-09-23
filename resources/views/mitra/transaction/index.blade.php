@extends('mitra.layout.index')
@section('title', $titlePage)
@section('content')
    <div class="main-content pt-0 hor-content">
        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="">
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $titlePage }}</h2>
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}"
                                    {{ $breadcrumb['active'] ? 'aria-current="page"' : '' }}>
                                    <a title="{{ $breadcrumb['title'] }}" href="{{ $breadcrumb['url'] }}">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
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
                                    <th id="transaction">ID Transaksi</th>
                                    <th id="name">Nama Customer</th>
                                    <th id="email">Email Customer</th>
                                    <th id="profit">Pendapatan</th>
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
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            {{ $row->transaction_id }}
                                        </td>
                                        <td>
                                            {{ $row->buyer_name }}
                                        </td>
                                        <td>
                                            {{ \App\Helpers\Common::obfuscateEmail($row->buyer_email) }}
                                        </td>
                                        <td>
                                            {{ Number::currency($row->total_income, in: 'IDR') }}
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
