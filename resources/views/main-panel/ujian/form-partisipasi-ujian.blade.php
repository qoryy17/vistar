@extends('main-panel.layout.main')
@section('title', $form_title)
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
                            <li class="breadcrumb-item">{{ $bc1 }}</li>
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
                                <form action="{{ route('exam-special.save') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Perhatian !</strong> Perhatikan pengisian anda sebelum menyimpan data.
                                    </div>

                                    <div class="row">
                                        @if (Crypt::decrypt($formParam) == 'update')
                                            <div class="col-md-12">
                                                <div class="form-group" hidden>
                                                    <label for="orderID">Order ID</label>
                                                    <input type="text" class="form-control" readonly name="orderID"
                                                        id="orderID" autocomplete="off" placeholder="Order ID"
                                                        value="{{ $examSpecial->id }}">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="customer">Customer / Partisipan
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control selectCustomer" name="customer" id="customer"
                                                    required>
                                                    <option value="">Pilih Customer/Partisipan</option>
                                                    @foreach ($customers as $partisipan)
                                                        @if ($examSpecial)
                                                            <option
                                                                @if ($examSpecial->customer_id == $partisipan->customer_id) selected @elseif (old('customer') == $partisipan->id) selected @endif
                                                                value="{{ $partisipan->id }}">
                                                                {{ $partisipan->name }} ({{ $partisipan->email }})
                                                            </option>
                                                        @else
                                                            <option value="{{ $partisipan->id }}"
                                                                @if (old('customer') == $partisipan->id) selected @endif>
                                                                {{ $partisipan->name }} ({{ $partisipan->email }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('customer')
                                                    <small class="text-danger">$ {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="produk">Produk Tryout
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control selectProduct" name="produk" id="produk"
                                                    required>
                                                    <option value="">Pilih Produk Tryout</option>
                                                    @foreach ($products as $item)
                                                        @if ($examSpecial)
                                                            <option
                                                                @if ($examSpecial->produk_tryout_id == $item->id) selected @elseif (old('produk') == $item->id) selected @endif
                                                                value="{{ $item->id }}">{{ $item->nama_tryout }}
                                                            </option>
                                                        @else
                                                            <option @if (old('produk') == $item->id) selected @endif
                                                                value="{{ $item->id }}">{{ $item->nama_tryout }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('produk')
                                                    <small class="text-danger">$ {{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group" hidden>
                                            <label for="formParameter">
                                                Form Parameter <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" placeholder="Parameter..."
                                                autocomplete="off" id="formParameter" name="formParameter"
                                                value="{{ $formParam }}" required readonly />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-default btn-sm btn-web">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <button type="reset" class="btn btn-sm btn-warning">
                                                <i class="fa fa-refresh"></i> Ulang
                                            </button>
                                            <a href="{{ route('exam-special.products') }}" class="btn btn-sm btn-dark">
                                                <i class="fa fa-reply"></i> Kembali
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->

            </div>
        </div>
    </div>
@endsection
