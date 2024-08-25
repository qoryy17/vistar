@extends('main-panel.layout.main')
@section('title', 'Vi Star Infonesia | ' . $page_title)
@section('content')
    <div class="main-content pt-0 hor-content">

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $page_title }}</h2>
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
                    <div class="col-lg-12">

                        <div class="card custom-card">
                            <div class="card-body">
                                <a href="{{ route('pengaturan.form-faq', ['param' => 'add', 'id' => 'faq']) }}"
                                    class="m-3 btn btn-sm btn-default btn-web">
                                    <i class="fa fa-plus"></i> Tambah FAQ
                                </a>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-bottom" id="example1">
                                        <thead>
                                            <tr>
                                                <th width="100%">Frequently Asked Questions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="white-space: normal">
                                                    <h5>1. Pertanyaan : Promo Tryout Hanya 100k</h5>
                                                    <p>Jawaban : Lorem ipsum,
                                                        dolor sit amet
                                                        consectetur adipisicing elit.
                                                        Sapiente, eius. Ex accusamus a fugiat voluptatem modi nam assumenda,
                                                        vitae eos debitis ut id veniam. Assumenda eveniet ex incidunt
                                                        aperiam cum.</p>
                                                    <span>Created at : {{ date('d-m-Y H:i:s') }} | Updated at :
                                                        {{ date('d-m-Y H:i:s') }}</span>
                                                    <div class="row mt-2">
                                                        <div class="col-md-12">
                                                            <a href="#" class="btn btn-sm btn-warning"><i
                                                                    class="fa fa-edit"></i> Edit</a>
                                                            <a href="#" class="btn btn-sm btn-danger"><i
                                                                    class="fa fa-trash"></i> Hapus</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="white-space: normal">
                                                    <h5>1. Pertanyaan : Promo Tryout Hanya 100k</h5>
                                                    <p>Jawaban : Lorem ipsum,
                                                        dolor sit amet
                                                        consectetur adipisicing elit.
                                                        Sapiente, eius. Ex accusamus a fugiat voluptatem modi nam assumenda,
                                                        vitae eos debitis ut id veniam. Assumenda eveniet ex incidunt
                                                        aperiam cum.</p>
                                                    <span>Created at : {{ date('d-m-Y H:i:s') }} | Updated at :
                                                        {{ date('d-m-Y H:i:s') }}</span>
                                                    <div class="row mt-2">
                                                        <div class="col-md-12">
                                                            <a href="#" class="btn btn-sm btn-warning"><i
                                                                    class="fa fa-edit"></i> Edit</a>
                                                            <a href="#" class="btn btn-sm btn-danger"><i
                                                                    class="fa fa-trash"></i> Hapus</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
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
