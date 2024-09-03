 @extends('main-web.layout.main')
 @section('title', $title)
 @section('content')
     <!-- Hero Start -->
     <section class="bg-profile d-table w-100 bg-primary"
         style="background: url('{{ asset('resources/images/bg.png') }}') center center;">
         <div class="container">
             <div class="row">
                 <div class="col-lg-12">
                     <div class="card public-profile border-0 rounded shadow" style="z-index: 1;">
                         <div class="card-body">
                             @if (session()->has('profilMessage'))
                                 <div class="alert bg-soft-primary fw-medium" role="alert"> <i
                                         class="uil uil-info-circle fs-5 align-middle me-1"></i>
                                     {{ session('profilMessage') }}
                                 </div>
                             @elseif (session()->has('errorMessage'))
                                 <div class="alert bg-soft-danger fw-medium" role="alert"> <i
                                         class="uil uil-info-circle fs-5 align-middle me-1"></i>
                                     {{ session('errorMessage') }}
                                 </div>
                             @endif
                             <div class="row align-items-center">
                                 <div class="col-lg-2 col-md-3 text-md-start text-center">
                                     <img src="{{ $customer ? asset('storage/user/' . $customer->foto) : asset('storage/user/user-default.png') }}"
                                         class="avatar avatar-large rounded-circle shadow d-block mx-auto" alt="">
                                 </div><!--end col-->

                                 <div class="col-lg-10 col-md-9">
                                     <div class="row align-items-end">
                                         <div class="col-md-7 text-md-start text-center mt-4 mt-sm-0">
                                             <h3 class="title mb-0">
                                                 {{ $customer ? $customer->nama_lengkap : Auth::user()->name }}
                                             </h3>
                                             <small class="text-muted h6 me-2">{{ $customer ? $customer->pendidikan : '' }}
                                                 {{ $customer ? $customer->jurusan : '' }}
                                             </small>

                                             <form action="{{ route('profils.ubah-foto') }}" method="POST"
                                                 enctype="multipart/form-data">
                                                 @csrf
                                                 @method('POST')
                                                 <div class="mt-2">
                                                     <input type="file" required class="form-control" name="foto" />
                                                 </div>
                                                 <button type="submit" class="btn btn-sm btn-primary mt-2">Ubah
                                                     Foto</button>
                                                 @error('foto')
                                                     <small class="text-danger mt-3">* {{ $message }}</small>
                                                 @enderror
                                             </form>
                                         </div><!--end col-->
                                     </div><!--end row-->
                                 </div><!--end col-->
                             </div><!--end row-->
                         </div>
                     </div>
                 </div><!--end col-->
             </div><!--end row-->
         </div><!--ed container-->
     </section><!--end section-->
     <!-- Hero End -->

     <!-- Profile Start -->
     <section class="section mt-60">
         <div class="container mt-lg-3">
             <div class="row">
                 <div class="col-lg-12 col-12">
                     <div class="card border-0 rounded shadow">
                         <div class="card-body">
                             <h5 class="text-md-start text-center">Informasi Akun Pengguna</h5>

                             <form action="{{ route('profils.ubah-profil') }}" method="POST">
                                 @csrf
                                 @method('POST')
                                 <div class="row mt-4">
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                             <div class="form-icon position-relative">
                                                 <i data-feather="user" class="fea icon-sm icons"></i>
                                                 <input name="namaLengkap" id="namaLengkap" type="text"
                                                     class="form-control ps-5" autocomplete="off"
                                                     placeholder="Nama Lengkap Anda..." required
                                                     value="{{ $customer ? $customer->nama_lengkap : old('namaLengkap') }}">
                                             </div>
                                             @error('namaLengkap')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="email" class="form-label">Email</label>
                                             <div class="form-icon position-relative">
                                                 <i data-feather="mail" class="fea icon-sm icons"></i>
                                                 <input name="email" id="email" type="email"
                                                     class="form-control ps-5" autocomplete="off"
                                                     placeholder="Email Anda..." required readonly
                                                     value="{{ $customer ? Auth::user()->email : '' }}">
                                             </div>
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="tanggalLahir" class="form-label">Tanggal Lahir</label>
                                             <div class="form-icon position-relative">
                                                 <i data-feather="calendar" class="fea icon-sm icons"></i>
                                                 <input name="tanggalLahir" id="tanggalLahir" type="text" required
                                                     class="form-control ps-5" placeholder="DD/MM/YYYY" maxlength="10"
                                                     value="{{ $customer->tanggal_lahir ? \Carbon\Carbon::createFromFormat('Y-m-d', $customer->tanggal_lahir)->format('d/m/Y') : old('tanggalLahir') }}">
                                             </div>
                                             @error('tanggalLahir')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                                             <div class="form-icon position-relative">
                                                 <i data-feather="user" class="fea icon-sm icons"></i>
                                                 <select name="jenisKelamin" id="jenisKelamin" class="form-control ps-5"
                                                     required>
                                                     <option value="">Pilih Jenis Kelamin</option>
                                                     @if ($customer)
                                                         <option value="LK"
                                                             @if ($customer->jenis_kelamin == 'LK') selected @endif>
                                                             Laki-Laki
                                                         </option>
                                                         <option value="PR"
                                                             @if ($customer->jenis_kelamin == 'PR') selected @endif>
                                                             Perempuan
                                                         </option>
                                                     @else
                                                         <option value="LK"
                                                             @if (old('jenisKelamin') == 'LK') selected @endif>
                                                             Laki-Laki
                                                         </option>
                                                         <option value="PR"
                                                             @if (old('jenisKelamin') == 'PR') selected @endif>
                                                             Perempuan
                                                         </option>
                                                     @endif
                                                 </select>
                                             </div>
                                             @error('jenisKelamin')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label class="form-label" for="kontak">Kontak. :</label>
                                             <div class="form-icon position-relative">
                                                 <i data-feather="phone" class="fea icon-sm icons"></i>
                                                 <input name="kontak" id="kontak" type="number"
                                                     class="form-control ps-5" required autocomplete="off"
                                                     placeholder="Kontak Anda..."
                                                     value="{{ $customer ? $customer->kontak : old('kontak') }}">
                                             </div>
                                             @error('kontak')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-lg-12">
                                         <div class="mb-3">
                                             <label class="form-label" for="alamat">Alamat</label>
                                             <div class="form-icon position-relative">
                                                 <i data-feather="map" class="fea icon-sm icons"></i>
                                                 <textarea name="alamat" autocomplete="off" id="alamat" rows="2" class="form-control ps-5"
                                                     placeholder="Alamat Anda..." required>{{ $customer ? $customer->alamat : old('alamat') }}</textarea>
                                             </div>
                                             @error('alamat')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label class="form-label" for="select2-provinsi">Provinsi :</label>
                                             <div class="form-icon position-relative">
                                                 <select name="provinsi" class="form-control" id="select2-provinsi"
                                                     required>
                                                     <option value="">Pilih Provinsi</option>
                                                 </select>
                                             </div>
                                             @error('provinsi')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label class="form-label" for="select2-kabupaten">Kota/Kabupaten :</label>
                                             <div class="form-icon position-relative">
                                                 <select name="kotaKab" class="form-control" id="select2-kabupaten">
                                                     <option value="">Pilih Kota/Kabupaten</option>
                                                 </select>
                                             </div>
                                             @error('kotaKab')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label class="form-label" for="select2-kecamatan">Kecamatan</label>
                                             <div class="form-icon position-relative">
                                                 <select name="kecamatan" class="form-control" id="select2-kecamatan">
                                                     <option value="">Pilih Kota/Kabupaten</option>
                                                 </select>
                                             </div>
                                             @error('kecamatan')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label class="form-label" for="pendidikan">Jenjang Pendidikan</label>
                                             <div class="form-icon position-relative">
                                                 <select name="pendidikan" class="form-control" id="pendidikan">
                                                     <option value="">Pilih Pendidikan</option>
                                                     @if ($customer)
                                                         <option value="SLTA"
                                                             @if ($customer->pendidikan == 'SLTA') selected @endif>
                                                             SLTA(SMA/SMK)</option>
                                                         <option value="D3"
                                                             @if ($customer->pendidikan == 'D3') selected @endif>Diploma (D3)
                                                         </option>
                                                         <option value="D4/S1"
                                                             @if ($customer->pendidikan == 'D4/S1') selected @endif>Sarjana
                                                             (D4/S1)</option>
                                                         <option value="S2"
                                                             @if ($customer->pendidikan == 'S2') selected @endif>Pasca
                                                             Sarjana (S2)</option>
                                                         <option value="S3"
                                                             @if ($customer->pendidikan == 'S3') selected @endif>Pasca
                                                             Sarjana (S3)</option>
                                                     @else
                                                         <option value="SLTA"
                                                             @if (old('pendidikan') == 'SLTA') selected @endif>
                                                             SLTA(SMA/SMK)</option>
                                                         <option value="D3"
                                                             @if (old('pendidikan') == 'D3') selected @endif>Diploma
                                                             (D3)
                                                         </option>
                                                         <option value="D4/S1"
                                                             @if (old('pendidikan') == 'D4/S1') selected @endif>Sarjana
                                                             (D4/S1)</option>
                                                         <option value="S2"
                                                             @if (old('pendidikan') == 'S2') selected @endif>Pasca
                                                             Sarjana (S2)</option>
                                                         <option value="S3"
                                                             @if (old('pendidikan') == 'S3') selected @endif>Pasca
                                                             Sarjana (S3)</option>
                                                     @endif
                                                 </select>
                                             </div>
                                             @error('pendidikan')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                     <div class="col-md-6">
                                         <div class="mb-3">
                                             <label class="form-label" for="jurusan">Program Studi/ Jurusan
                                                 <small>(Silahkan isi sendiri)</small></label>
                                             <div class="form-icon position-relative">
                                                 <i data-feather="file" class="fea icon-sm icons"></i>
                                                 <input name="jurusan" id="jurusan" type="text"
                                                     class="form-control ps-5" required autocomplete="off"
                                                     placeholder="Program Studi/ Jurusan..."
                                                     value="{{ $customer ? $customer->jurusan : old('jurusan') }}">
                                             </div>
                                             @error('jurusan')
                                                 <small class="text-danger mt-3">* {{ $message }}</small>
                                             @enderror
                                         </div>
                                     </div><!--end col-->
                                 </div><!--end row-->
                                 <div class="row">
                                     <div class="col-sm-12">
                                         <button type="submit" class="btn btn-primary">Simpan Informasi</button>
                                     </div><!--end col-->
                                 </div><!--end row-->
                             </form><!--end form-->

                             <div class="row">
                                 <div class="col-md-12 mt-4 pt-2">
                                     <h5>Ubah Password</h5>
                                     <form action="{{ route('profils.ubah-password') }}" method="POST">
                                         @csrf
                                         @method('POST')
                                         <div class="row mt-4">
                                             @if (Auth::user()->google_id == null and Auth::user()->password != null)
                                                 <div class="col-lg-12">
                                                     <div class="mb-3">
                                                         <label class="form-label" for="passwordLama">Password Lama <small
                                                                 class="text-danger">*
                                                                 Kosongkan jika tidak ingin
                                                                 mengganti</small></label>
                                                         <div class="form-icon position-relative">
                                                             <i data-feather="key" class="fea icon-sm icons"></i>
                                                             <input type="password" class="form-control ps-5"
                                                                 placeholder="Password Lama" id="passwordLama"
                                                                 autocomplete="off" name="passwordLama">
                                                         </div>
                                                         @error('passwordLama')
                                                             <small class="text-danger">* {{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div><!--end col-->
                                             @elseif(Auth::user()->google_id != null and Auth::user()->password != null)
                                                 <div class="col-lg-12">
                                                     <div class="mb-3">
                                                         <label class="form-label" for="passwordLama">Password Lama <small
                                                                 class="text-danger">*
                                                                 Kosongkan jika tidak ingin
                                                                 mengganti</small></label>
                                                         <div class="form-icon position-relative">
                                                             <i data-feather="key" class="fea icon-sm icons"></i>
                                                             <input type="password" class="form-control ps-5"
                                                                 placeholder="Password Lama" id="passwordLama"
                                                                 autocomplete="off" name="passwordLama">
                                                         </div>
                                                         @error('passwordLama')
                                                             <small class="text-danger">* {{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div><!--end col-->
                                             @endif
                                             <div class="col-lg-12">
                                                 <div class="mb-3">
                                                     <label class="form-label" for="passwordBaru">Password Baru <small
                                                             class="text-danger">*
                                                             Kosongkan jika tidak ingin
                                                             mengganti</small></label>
                                                     <div class="form-icon position-relative">
                                                         <i data-feather="key" class="fea icon-sm icons"></i>
                                                         <input type="password" class="form-control ps-5"
                                                             placeholder="New Password" name="passwordBaru"
                                                             id="passwordBaru">
                                                     </div>
                                                     @error('passwordBaru')
                                                         <small class="text-danger">* {{ $message }}</small>
                                                     @enderror
                                                 </div>
                                             </div><!--end col-->

                                             <div class="col-lg-12">
                                                 <div class="mb-3">
                                                     <label class="form-label" for="konfirmasiPassword">Konfirmasi
                                                         Password <small class="text-danger">* Kosongkan jika tidak ingin
                                                             mengganti</small></label>
                                                     <div class="form-icon position-relative">
                                                         <i data-feather="key" class="fea icon-sm icons"></i>
                                                         <input type="password" class="form-control ps-5"
                                                             placeholder="Konfirmasi Password" id="konfirmasiPassword"
                                                             name="konfirmasiPassword">
                                                     </div>
                                                     @error('passwordBaru')
                                                         <small class="text-danger">* {{ $message }}</small>
                                                     @enderror
                                                 </div>
                                             </div><!--end col-->

                                             <div class="col-lg-12 mt-2 mb-0">
                                                 <button class="btn btn-primary">Simpan Password</button>
                                             </div><!--end col-->
                                         </div><!--end row-->
                                     </form>
                                 </div><!--end col-->
                             </div><!--end row-->
                         </div>
                     </div>
                 </div><!--end col-->
             </div><!--end row-->
         </div><!--end container-->
     </section><!--end section-->
     <!-- Profile Setting End -->
 @endsection
