@extends('main-panel.layout.main')
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

                <div class="row">
                    <!-- Left side -->
                    <div class="col-lg-4">
                        <div class="card overflow-hidden custom-card ">
                            <div id="lightgallery">
                                <div data-responsive="{{ asset('storage/' . $sertikom->thumbnail) }}"
                                    data-src="{{ asset('storage/' . $sertikom->thumbnail) }}"
                                    data-sub-html="<h4>{{ $sertikom->produk }}</h4><p>{{ $sertikom->topik }}</p>">
                                    <a href="" class="wd-100p">
                                        <img class="img-fluid" src="{{ asset('storage/' . $sertikom->thumbnail) }}"
                                            alt="Thumbnail {{ $sertikom->produk }}"
                                            title="Thumbnail {{ $sertikom->produk }}" loading="lazy" />
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="text-center border-bottom p-3">Informasi Produk</h5>
                                <div class="mt-3">

                                    <h5>Jadwal Pelatihan</h5>
                                    <p class="m-0 p-0">
                                        Tanggal :
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $sertikom->tanggal_mulai)->format('d/m/Y') }}
                                        -
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $sertikom->tanggal_selesai)->format('d/m/Y') }}
                                        <br>
                                        Waktu : {{ $sertikom->jam_mulai }} s/d {{ $sertikom->jam_selesai }} Wib
                                    </p>
                                </div>

                                <hr>
                                <div class="mb-3">
                                    <h5>Informasi Link</h5>
                                    <ul class="mb-0 pb-0">
                                        @if ($sertikom->link_wa != null)
                                            <li>
                                                <a target="_blank" title="Gabung WhatsApp Group"
                                                    href="{{ $sertikom->link_wa }}">
                                                    Gabung WhatsApp Group
                                                </a>
                                            </li>
                                        @endif
                                        @if ($sertikom->link_zoom != null)
                                            <li>
                                                <a target="_blank" title="Gabung Zoom Meeting"
                                                    href="{{ $sertikom->link_zoom }}">
                                                    Zoom Meeting
                                                </a>
                                            </li>
                                        @endif
                                        @if ($sertikom->link_rekaman != null)
                                            <li>
                                                <a target="_blank" title="Unduh Rekaman"
                                                    href="{{ $sertikom->link_rekaman }}">
                                                    Rekaman Pelatihan
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <a href="{{ route('sertikom.product', ['category' => 'pelatihan']) }}"
                                    class="btn btn-block btn-primary btn-sm">
                                    <i class="fa fa-reply"></i>
                                    Kembali
                                </a>

                            </div>
                        </div>
                    </div>
                    <!-- End left side -->

                    <!-- Left right -->
                    <div class="col-lg-8">
                        <div class="card custom-card">
                            <div class="card-body">
                                <h3 class="text-primary">
                                    "{{ $sertikom->produk }}"
                                </h3>
                                <p class="m-0 p-0" style="text-align: justify;">
                                    Topik : {{ $sertikom->topik }}
                                </p>
                                <p class="m-0 p-0">
                                    <span>Instuktur : {{ $sertikom->instruktur }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="card custom-card">
                            <div class="card-body">
                                <h5 class="p-3 m-0 mb-0 pb-0">
                                    Tahapan Proses Pelatihan : @if ($currentStep == null)
                                        <span class="text-primary">Belum Dimulai</span>
                                    @elseif($currentStep == 'Proses')
                                        <span class="text-primary">Pelatihan Sedang Berlangsung</span>
                                    @elseif($currentStep == 'Tugas')
                                        <span class="text-primary">Sedang Mengumpulkan Pre Test & Post Test</span>
                                    @elseif($currentStep == 'Review')
                                        <span class="text-primary">Sedang Mereview Pre Test & Post Test</span>
                                    @elseif($currentStep == 'Selesai')
                                        <span class="text-primary">Pelatihan Telah Berakhir</span>
                                    @endif
                                </h5>
                                @if ($currentStep == null)
                                    <div class="alert alert-warning" role="alert">
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <p style="text-align: justify;">
                                            <strong>Informasi</strong> <br>
                                            Tahapan ini dibuat apabila pelatihan segera dimulai, dan status produk pada
                                            pelatihan
                                            akan berubah menjadi <strong>"Sold Out"</strong>
                                        </p>
                                    </div>
                                @endif
                                @error('Tahapan')
                                    <div class="alert alert-warning" role="alert">
                                        >* {{ $message }}
                                    </div>
                                @enderror

                                <div class="p-3 m-0 mt-0 pt-0">
                                    <button data-bs-target="#modalTahapan" data-bs-toggle="modal"
                                        class="btn btn-sm btn-default btn-web">
                                        <i class="fa fa-plus"></i>
                                        @if ($currentStep == null)
                                            Buat
                                        @else
                                            Edit
                                        @endif Tahapan
                                    </button>
                                    @if ($currentStep != null)
                                        <button
                                            onclick='swal({
                                                title: "Hapus Hapus Tahapan",
                                                text: "Data yang dihapus tidak bisa dikembalikan \n Seluruh tahapan akan diset ulang",
                                                type: "info",
                                                showCancelButton: true,
                                                closeOnConfirm: false,
                                                showLoaderOnConfirm: true }, function () 
                                                { 
                                                    setTimeout(function(){  
                                                        document.getElementById("delete-form").submit();
                                                }, 2000); });'
                                            class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                            Hapus Tahapan
                                        </button>
                                        <form id="delete-form"
                                            action="{{ route('sertikom.delete-step', ['id' => Crypt::encrypt($sertikom->id), 'category' => 'pelatihan']) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>

                                <!-- Tahapan modal -->
                                <form action="{{ route('sertikom.create-step') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="modal fade" id="modalTahapan">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Tahapan Pelatihan</h6>
                                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                        type="button">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if (Crypt::decrypt($param) == 'update')
                                                        <div class="form-group" hidden>
                                                            <input type="text" value="{{ Crypt::encrypt($stepID) }}"
                                                                readonly name="IDStep" class="form-control">
                                                        </div>
                                                    @endif
                                                    <div class="form-group" hidden>
                                                        <input type="text" value="{{ Crypt::encrypt($sertikom->id) }}"
                                                            readonly name="IDSertikom" class="form-control">
                                                    </div>
                                                    <div class="form-group" hidden>
                                                        <input type="text" value="{{ Crypt::encrypt('Pelatihan') }}"
                                                            readonly name="Category" class="form-control">
                                                    </div>
                                                    <div class="form-group" hidden>
                                                        <input type="text" value="{{ $stepCode }}" readonly
                                                            name="Kode" class="form-control">
                                                    </div>
                                                    <div class="form-group" hidden>
                                                        <input type="text" value="{{ $param }}" readonly
                                                            name="Param" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Tahapan">Pilih Tahapan
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select class="form-control" name="Tahapan" id="Tahapan"
                                                            required>
                                                            <option value="">-- Pilih Tahapan --</option>
                                                            <option value="Proses"
                                                                @if ($currentStep == 'Proses') selected @endif>Proses
                                                            </option>
                                                            <option value="Tugas"
                                                                @if ($currentStep == 'Tugas') selected @endif>Tugas
                                                            </option>
                                                            <option value="Review"
                                                                @if ($currentStep == 'Review') selected @endif>Review
                                                            </option>
                                                            <option value="Selesai"
                                                                @if ($currentStep == 'Selesai') selected @endif>Selesai
                                                            </option>
                                                        </select>
                                                        @error('Tahapan')
                                                            <small class="text-danger">* {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <h6>Keterangan Tahapan</h6>
                                                    <ul>
                                                        <li style="text-align: justify;"><strong>Proses</strong> :
                                                            Kegiatan
                                                            pelatihan yang
                                                            sedang berlangsung menyampaikan
                                                            materi, diskusi ataupun tanya jawab</li>
                                                        <li style="text-align: justify;"><strong>Tugas</strong> :
                                                            Kegiatan
                                                            pelatihan yang
                                                            sedang berlangsung mengerjakan
                                                            tugas pre test & post test</li>
                                                        <li style="text-align: justify;"><strong>Review</strong> :
                                                            Hasil Pre Test & Post Test sedang ditinjau instuktur</li>
                                                        <li style="text-align: justify;"><strong>Selesai</strong> :
                                                            Kegiatan pelatihan
                                                            selesai, peserta dapat mengunduh
                                                            sertifikat</li>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm btn-block btn-primary" type="submit">
                                                        <i class="fa fa-save"></i> Simpan
                                                    </button>
                                                    <button class="btn btn-sm btn-block btn-warning"
                                                        data-bs-dismiss="modal" type="button">
                                                        <i class="fa fa-times"></i> Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example1">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="93%">Peserta</th>
                                                <th width="2%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @if ($participant)
                                                @foreach ($participant->get() as $row)
                                                    <tr>
                                                        <td>{{ $no }}</td>
                                                        <td>
                                                            {{ $row->nama }}
                                                            <br>Kode Peserta : {{ $row->kode_peserta }} | Kontak : Kontak :
                                                            {{ $row->kontak }}
                                                            <p class="m-0 p-0">
                                                                @if ($row->link_pretest != null)
                                                                    <a href="{{ $row->link_pretest }}" target="_blank"
                                                                        title="Link Pre Test">
                                                                        Link Pre Test
                                                                    </a>
                                                                @endif
                                                                @if ($row->link_posttest != null)
                                                                    <a href="{{ $row->link_posttest }}" target="_blank"
                                                                        title="Link Post Test">
                                                                        Link Pre Test
                                                                    </a>
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <button
                                                                onclick='swal({
                                                                        title: "Hapus Peserta",
                                                                        text: "Peserta yang dihapus tidak bisa dikembalikan",
                                                                        type: "info",
                                                                        showCancelButton: true,
                                                                        closeOnConfirm: false,
                                                                        showLoaderOnConfirm: true }, function () 
                                                                        { 
                                                                            setTimeout(function(){  
                                                                                document.getElementById("delete-participant{{ $no }}").submit();
                                                                        }, 2000); });'
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <form id="delete-participant{{ $no }}"
                                                                action="{{ route('sertikom.delete-participant', ['id' => Crypt::encrypt($row->id), 'category' => 'pelatihan', 'idSertikom' => Crypt::encrypt($sertikom->id)]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $no++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End left right -->
                </div>
            </div>
        </div>
    </div>
@endsection
