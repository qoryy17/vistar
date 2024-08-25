@extends('customer-panel.layout.main')
@section('title', 'Vi Star | ' . $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $page_title }}</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Event</a></li>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Informasi Bimbel</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <a href="#" title="Klik untuk melihat detil">
                                                        <h5 style="color: #0075B8;">Event-Bimbel-907997979 (CPNS)</h5>
                                                    </a>
                                                    <div>
                                                        Jadwal Mulai : <b>{{ date('d-m-Y H:i:s') }}</b> | Selesai :
                                                        <b>{{ date('d-m-Y H:i:s') }}</b> <br>
                                                        <span class="mt-1 mb-2 badge bg-success mt-2">Created at :
                                                            {{ date('d-m-Y H:i:s') }}
                                                        </span>
                                                    </div>
                                                    <div class="mt-1" style="white-space: normal; text-align:justify;">
                                                        Testimoni : Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                                        Eligendi, incidunt reprehenderit enim, eveniet beatae expedita
                                                        laborum ut repellendus, cum deserunt doloremque id. Labore mollitia
                                                        porro nihil tenetur quia. Tempore, magnam?
                                                        <br>
                                                        Rating : <i class="fa fa-star"
                                                            style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                        <i class="fa fa-star" style="color: rgb(255, 207, 16);"></i>
                                                        <br>
                                                        <a class="btn btn-sm btn-default btn-web" target="_blank"
                                                            href="https://drive.google.com/drive/folders/1pM-sdBWUFdu9D_X-ucI4adOw6VCAYba_">
                                                            <i class="fa fa-download"></i> Unduh Rekaman
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div>
                                    <h6>Pertemuan Bimbel Online</h6>
                                    <h6 style="padding: 0px; margin:0px;" class="mb-2"><span class="fs-30 me-2"
                                            style="color: #0075B8;">Pertemuan Ke : 1</span><span
                                            class="badge bg-success">CPNS</span>
                                    </h6>
                                    <span class="text-muted tx-12">Silahkan mengisi daftar hadir
                                        absensi bimbel
                                        online.</span>
                                    <a href="{{ url('/') }}" class="btn btn-block btn-default mt-2 btn-web"><i
                                            class="fa fa-check-circle"></i> Klik
                                        untuk
                                        mengisi absensi</a>
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
