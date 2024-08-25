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
                                  @php
                                      $no = 1;
                                  @endphp
                                  @foreach ($tryout as $row)
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
                                          <td class="text-center">{{ Number::currency($row->harga_promo, in: 'IDR') }}</td>
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
                              </tbody>
                          </table>
                      </div>
                  </div><!--end col-->
              </div><!--end row-->

          </div><!--end container-->
      </section><!--end section-->
      <!-- End -->
  @endsection
