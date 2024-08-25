@extends('main-panel.layout.main')
@section('title', 'Vi Star Indonesia | ' . $page_title)
@section('content')
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
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Customer</th>
                                                <th>Kode Referral</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Kelola</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($referral as $row)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>
                                                        {{ $row->nama_lengkap }}
                                                    </td>
                                                    <td>
                                                        @if ($row->kode_referral == null)
                                                            <span class="badge bg-warning"> Belum melengkapi profil</span>
                                                        @else
                                                            {{ $row->kode_referral }}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($row->kode_referral == null)
                                                            <span class="badge bg-warning"> Belum melengkapi profil</span>
                                                        @else
                                                            {{ $row->created_at }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->kode_referral == null)
                                                            <span class="badge bg-warning"> Belum melengkapi profil</span>
                                                        @else
                                                            {{ $row->updated_at }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->kode_referral != null)
                                                            <a href="{{ route('referral.detil', ['kodeReferral' => $row->kode_referral, 'namaLengkap' => $row->nama_lengkap]) }}"
                                                                title="Detil Referral" class="btn btn-primary btn-sm">
                                                                <i class="fa fa-search"></i>
                                                            </a>
                                                        @endif
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
                <!-- End Row -->
            </div>
        </div>
    </div>
@endsection
