  @extends('main-web.layout.main')
  @section('title', $title)
  @section('content')
      @if ($tryout)
          @foreach ($tryout as $order)
          @endforeach
      @endif
      <!-- Start -->
      <section class="section" style="padding-top: 100px;">
          <div class="container">
              <div class="row justify-content-center">
                  <div class="col-12 text-center">
                      <div class="section-title mb-4 pb-2">
                          <h4 class="title mb-4">Pembayaran {{ $order->nama_tryout }}</h4>
                          <p class="text-muted para-desc mb-0 mx-auto">Berikut informasi detail pesanan anda, periksa
                              terlebih dahulu sebelum melakukan pembayaran, jika informasi sudah benar silahkan klik
                              tombol bayar untuk melakukan proses pembayaran</p>
                      </div>
                  </div><!--end col-->
              </div><!--end row-->
              <div class="row">
                  <div class="col-12">
                      <div class="table-responsive bg-white shadow rounded">
                          <table class="table mb-0 table-center">
                              <thead>
                                  <tr>
                                      <th class="border-bottom text-start py-3" style="min-width: 300px;">Paket Tryout</th>
                                      <th class="border-bottom text-center py-3" style="min-width: 150px;">Harga</th>
                                      <th class="border-bottom text-center py-3" style="min-width: 150px;">Promo</th>
                                  </tr>
                              </thead>

                              <tbody>
                                  <tr class="shop-list">
                                      <td class="text-start">
                                          <div class="align-items-center">
                                              <h6>{{ $order->nama_tryout }}</h6>
                                              <p>Keterangan : {{ $order->keterangan }}</p>
                                          </div>
                                      </td>
                                      <td class="text-center">{{ Number::currency($order->harga, in: 'IDR') }}</td>
                                      <td class="text-center">{{ Number::currency($order->harga_promo, in: 'IDR') }}</td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div><!--end col-->
              </div><!--end row-->
              <div class="row">
                  <div class="col-lg-6 col-md-6 mt-4 pt-2">
                      <br>
                      <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre>
                      {{-- <div id="snap-container"></div> --}}
                  </div>
                  <div class="col-lg-6 col-md-6 ms-auto mt-4 pt-2">
                      <div class="table-responsive bg-white rounded shadow">
                          <table class="table table-center table-padding mb-0">
                              <tbody>
                                  <tr class="bg-light">
                                      <td class="h6 ps-4 py-3" style="vertical-align: top;">
                                          <button id="buttonReferral" class="btn btn-pills btn-soft-primary">Pakai
                                              Kode</button>
                                      </td>
                                      <td class="text-end fw-bold pe-4">
                                          <input id="kodeReferral" type="text" class="form-control"
                                              placeholder="Masukan Kode Referral">
                                          <small id="message" style="color: green; display: none; font-size: 12px;">Kode
                                              referral
                                              valid !</small>
                                      </td>
                                  </tr>
                                  <tr class="bg-light">
                                      <td class="h6 ps-4 py-3">Total</td>
                                      <td class="text-end fw-bold pe-4">
                                          @if ($order->harga != null)
                                              {{ Number::currency($order->harga, in: 'IDR') }}
                                          @else
                                              {{ Number::currency($order->harga_promo, in: 'IDR') }}
                                          @endif
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                      <div class="mt-4 pt-2 text-end">
                          <a href="{{ route('mainweb.keranjang') }}" class="btn btn-pills btn-soft-warning">
                              <i class="mdi mdi-reply"></i> Kembali
                          </a>
                          <button id="pay-button" type="submit" class="btn btn-pills btn-soft-primary">
                              Bayar Sekarang <i class="mdi mdi-arrow-right"></i>
                          </button>

                      </div>
                  </div><!--end col-->
              </div><!--end row-->
          </div><!--end container-->
      </section><!--end section-->
      <!-- End -->
      <script src="{{ url('resources/web/dist/assets/js/jquery-3.7.1.min.js') }}"></script>
      <script src="https://app.sandbox.midtrans.com/snap/snap.js"
          data-client-key="{{ config('services.midtrans.client_key') }}"></script>
      <script type="text/javascript">
          document.getElementById('buttonReferral').addEventListener('click', function() {
              const referralCodeInput = document.getElementById('kodeReferral');
              const referralCode = referralCodeInput.value.trim();
              const messageElement = document.getElementById('message');

              fetch("{{ route('orders.check-referral') }}", {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({
                          referral_code: referralCode
                      })
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.status === 'success') {
                          // Tampilkan pesan sukses
                          messageElement.style.display = 'block';
                          messageElement.style.color = 'green';
                          messageElement.textContent = data.message;

                          // Set input menjadi readonly
                          referralCodeInput.readOnly = true;

                          // Sembunyikan tombol
                          document.getElementById('buttonReferral').style.display = 'none';

                      } else {
                          // Tampilkan pesan error
                          messageElement.style.display = 'block';
                          messageElement.style.color = 'red';
                          messageElement.textContent = data.message;
                      }
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      messageElement.style.display = 'block';
                      messageElement.style.color = 'red';
                      messageElement.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                  });
          });


          $('#pay-button').click(function(event) {
              event.preventDefault();

              const referralCodeInput = document.getElementById('kodeReferral');
              const referralCode = referralCodeInput.value.trim();

              $.post("{{ route('orders.pay-order') }}", {
                      _method: 'POST',
                      _token: '{{ csrf_token() }}',
                      id: "{{ Crypt::encrypt($order->id) }}",
                      referralCode: referralCode,
                  },
                  function(data, status) {

                      snap.pay(data.snap_token, {
                          onSuccess: function(result) {
                              window.location.href = "{{ route('site.pembelian') }}";
                          },
                          onPending: function(result) {
                              window.location.href = "{{ route('site.pembelian') }}";

                          },
                          onError: function(result) {
                              window.location.href = "{{ route('site.pembelian') }}";
                          }

                      });
                      return false;
                  });
          });
      </script>
  @endsection
