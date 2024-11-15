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

                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered border-bottom" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pelatihan</th>
                                        <th>Harga</th>
                                        <th>Topik</th>
                                        <th>Pembelian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($sertikom as $row)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $row->produk }}</td>
                                            <td>Rp.{{ Number::Format($row->harga, 0) }}</td>
                                            <td>{{ $row->topik }}</td>
                                            <td>{{ $row->created_at }}</td>
                                            <td>
                                                <a class="btn btn-primary btn-sm btn-block"
                                                    href="{{ route('customer.detail-sertikom', ['category' => 'pelatihan', 'id' => Crypt::encrypt($row->id)]) }}"
                                                    title="Detil Pelatihan">
                                                    Lihat
                                                    <i class="fa fa-search"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
