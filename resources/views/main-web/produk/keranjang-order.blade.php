  @extends('main-web.layout.main')
  @section('title', $title)
  @section('content')
      <!-- Start -->
      <section class="section" style="padding-top: 100px;">
          <div class="container">
              <div class="row justify-content-center">
                  <div class="col-12 text-center">
                      <div class="section-title mb-4 pb-2">
                          <h4 class="title mb-4">Keranjang Pesanan Anda</h4>
                          <p class="text-muted para-desc mb-0 mx-auto">Berikut keranjang pesanan produk tryout, anda dapat
                              melakukan pembayaran dan juga menghapus item produk keranjang</p>
                      </div>
                  </div><!--end col-->
              </div><!--end row-->
              @if (session()->has('successMessage'))
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="alert bg-soft-primary fw-medium" role="alert"> <i
                                  class="uil uil-info-circle fs-5 align-middle me-1"></i>
                              {{ session('successMessage') }}
                          </div>
                      </div>
                  </div>
              @elseif (session()->has('errorMessage'))
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="alert bg-soft-danger fw-medium" role="alert"> <i
                                  class="uil uil-info-circle fs-5 align-middle me-1"></i>
                              {{ session('errorMessage') }}
                          </div>
                      </div>
                  </div>
              @endif

              <div class="row">
                  <div class="col-12">
                      <div class="table-responsive bg-white shadow rounded">
                          <table class="table mb-0 table-center">
                              <thead>
                                  <tr>
                                      <th class="border-bottom text-start py-3" style="min-width: 10px;">No</th>
                                      <th class="border-bottom text-start py-3" style="min-width: 300px;">Paket Tryout</th>
                                      <th class="border-bottom text-center py-3" style="min-width: 150px;">Harga</th>
                                      <th class="border-bottom text-center py-3" style="min-width: 150px;">Promo</th>
                                      <th class="border-bottom text-center py-3" style="min-width: 150px;">Aksi</th>
                                  </tr>
                              </thead>

                              <tbody>
                                  @if ($tryout->first())
                                      @php
                                          $no = 1;
                                      @endphp
                                      @foreach ($tryout->get() as $row)
                                          <tr class="shop-list">
                                              <td>
                                                  {{ $no }}
                                              </td>
                                              <td>
                                                  <div class="d-flex align-items-center">
                                                      <h6 class="mb-0 ms-3">{{ $row->nama_tryout }}</h6>
                                                  </div>
                                              </td>
                                              <td class="text-center">{{ Number::currency($row->harga, in: 'IDR') }}</td>
                                              <td class="text-center">{{ Number::currency($row->harga_promo, in: 'IDR') }}
                                              </td>
                                              <td class="text-center">
                                                  <button onclick="hapusItem{{ $no }}()"
                                                      class="btn btn-pills btn-soft-danger"">Hapus</button>
                                                  <a href="{{ route('orders.detail-pesanan', ['params' => Crypt::encrypt($row->id)]) }}"
                                                      class="btn btn-pills btn-soft-primary">Bayar</a>
                                                  <form id="formHapusItem{{ $no }}"
                                                      action="{{ route('mainweb.hapus-item', ['id' => Crypt::encrypt($row->id)]) }}"
                                                      method="POST">
                                                      @csrf
                                                      @method('DELETE')
                                                  </form>
                                                  <script>
                                                      function hapusItem{{ $no }}() {
                                                          document.getElementById('formHapusItem{{ $no }}').submit();
                                                      }
                                                  </script>
                                              </td>
                                          </tr>
                                          @php
                                              $no++;
                                          @endphp
                                      @endforeach
                                  @else
                                      <tr>
                                          <td colspan="5">
                                              <div class="alert bg-soft-warning fw-medium" role="alert"> <i
                                                      class="uil uil-info-circle fs-5 align-middle me-1"></i>
                                                  Anda belum menambahkan produk apapun dikeranjang ini !
                                              </div>

                                          </td>
                                      </tr>
                                  @endif
                              </tbody>
                          </table>
                      </div>
                  </div><!--end col-->
              </div><!--end row-->

              <div class="row mt-6">
                  <div class="col-lg-12 col-md-12 mt-4 pt-2">
                      <h6> Rekomendasi Produk Tryout Pilihan</h6>
                  </div>
                  @php
                      $no = 1;
                  @endphp
                  @foreach ($allProduk as $row)
                      <div class="col-lg-4 col-md-6">
                          <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                              <div class="card-body p-0">
                                  <div class="d-inline-block">
                                      <img class="img-fluid mb-3" src="{{ asset('storage/tryout/' . $row->thumbnail) }}"
                                          alt="thubmnail" loading="lazy">
                                  </div>
                                  <span
                                      class="py-2 px-2 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">{{ $row->nama_tryout }}</span>
                                  <h3 class="fw-bold mb-0 mt-3">
                                      {{ Number::currency($row->harga, in: 'IDR') }}</h2>
                                      @if ($row->harga_promo != null and $row->harga_promo != 0)
                                          <p class="text-muted">Promo {{ Number::currency($row->harga_promo, in: 'IDR') }}
                                          </p>
                                      @endif
                                      <div class="accordion" id="buyingquestion">
                                          <div class="accordion-item rounded">
                                              <h2 class="accordion-header" id="headingOne{{ $no }}">
                                                  <button class="accordion-button border-0 bg-light" type="button"
                                                      data-bs-toggle="collapse"
                                                      data-bs-target="#collapseOne{{ $no }}" aria-expanded="true"
                                                      aria-controls="collapseOne{{ $no }}">
                                                      Fitur dalam paket ini
                                                  </button>
                                              </h2>
                                              <div id="collapseOne{{ $no }}"
                                                  class="accordion-collapse border-0 collapse "
                                                  aria-labelledby="headingOne{{ $no }}"
                                                  data-bs-parent="#buyingquestion">
                                                  <div class="accordion-body text-muted">
                                                      <ul class="list-unstyled pt-3 border-top">
                                                          <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                                                  <i
                                                                      class="uil uil-check-circle align-middle"></i></span>Ujian
                                                              Tidak Terbatas
                                                          </li>
                                                          @if ($row->nilai_keluar == 'Y')
                                                              <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                                                      <i
                                                                          class="uil uil-check-circle align-middle"></i></span>Hasil
                                                                  Ujian
                                                              </li>
                                                          @endif

                                                          @if ($row->grafik_evaluasi == 'Y')
                                                              <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                                                      <i
                                                                          class="uil uil-check-circle align-middle"></i></span>Grafik
                                                                  Hasil
                                                                  Ujian
                                                              </li>
                                                          @endif

                                                          @if ($row->review_pembahasan == 'Y')
                                                              <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                                                      <i
                                                                          class="uil uil-check-circle align-middle"></i></span>Review
                                                                  Pembahasan
                                                                  Soal
                                                              </li>
                                                          @endif

                                                          {{-- <li class="h6 text-muted mb-0"><span class="icon h5 me-2"><i
                                                 class="uil uil-check-circle align-middle"></i></span>Akses Bagikan Referal
                                     </li> --}}
                                                          <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                                                  <i
                                                                      class="uil uil-check-circle align-middle"></i></span>Masa
                                                              Aktif
                                                              {{ $row->masa_aktif }} Hari
                                                          </li>
                                                      </ul>
                                                  </div>
                                              </div>
                                          </div>

                                      </div>
                                      <div class="mt-4">
                                          <div class="d-grid">
                                              <a onclick="submitForm{{ $no }}()" href="javascript:void(0)"
                                                  class="btn btn-pills btn-primary">Beli
                                                  Sekarang</a>
                                              <form id="keranjangForm{{ $no }}"
                                                  action="{{ route('mainweb.pesan-tryout-berbayar', ['idProdukTryout' => Crypt::encrypt($row->id)]) }}"
                                                  method="POST">
                                                  @csrf
                                                  @method('POST')
                                              </form>
                                              <script>
                                                  function submitForm{{ $no }}() {
                                                      document.getElementById('keranjangForm{{ $no }}').submit();
                                                  }
                                              </script>
                                          </div>
                                      </div>
                              </div>
                          </div>
                      </div><!--end col-->
                      @php
                          $no++;
                      @endphp
                  @endforeach
              </div>
          </div><!--end container-->
      </section><!--end section-->
      <!-- End -->
  @endsection
