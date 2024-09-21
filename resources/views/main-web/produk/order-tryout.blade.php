@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    @php
        $total = 0;
    @endphp
    <!-- Start -->
    <section class="section" style="margin-top: 50px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h1 class="fs-3 title mb-4">Pembayaran</h1>
                        <p class="text-muted para-desc mb-0 mx-auto">
                            Berikut informasi detail pesanan anda, periksa terlebih dahulu sebelum melakukan pembayaran,
                            jika informasi sudah benar silahkan klik tombol <strong>Bayar Sekarang</strong> untuk melakukan
                            proses pembayaran
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            <div class="row">
                <div class="col-12">
                    {{--  SEO Purpose  --}}
                    <h2 class="hide fs-5">Data Pembelian</h2>
                    <div class="table-responsive bg-white shadow rounded">
                        <table class="table mb-0 table-center">
                            <thead>
                                <tr>
                                    <th class="border-bottom text-start py-3" style="min-width: 300px;">Produk</th>
                                    <th class="border-bottom text-end" style="min-width: 150px;">Harga</th>
                                    <th class="border-bottom text-end" style="min-width: 150px;">Promo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr class="shop-list">
                                        <td class="text-start">
                                            <div class="align-items-center">
                                                <h3 class="fs-6">{{ $order->nama_tryout }}</h3>
                                                <div class="fs-6 d-inline">
                                                    <span>Keterangan :</span>
                                                    <h4 class="fs-6 fw-normal d-inline">
                                                        {{ $order->keterangan }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">Rp. {{ number_format($order->harga, 0) }}</td>
                                        <td class="text-end">Rp. {{ number_format($order->harga_promo, 0) }}
                                        </td>
                                    </tr>

                                    @php
                                        if ($order->harga_promo !== 0 && $order->harga_promo !== null) {
                                            $total += $order->harga_promo;
                                        } else {
                                            $total += $order->harga;
                                        }
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            <div class="row">
                <div class="col-lg-6 col-md-6 ms-auto mt-4 pt-2">
                    <div class="table-responsive bg-white rounded shadow">
                        <table class="table table-center table-padding mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th id="total" class="h6 ps-4 py-3">
                                        <h2 class="fs-6">Total</h2>
                                    </th>
                                    <th id="total" class="text-end fw-bold pe-4">
                                        <h3 class="fs-6">Rp. {{ number_format($total, 0) }}</h3>
                                    </th>
                                </tr>
                            </thead>
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
@endsection

@section('styles')
    <!-- Internal Sweet-Alert css-->
    <link href="{{ asset('resources/spruha/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
    <style>
        /* Fix style.css, why iframe set to auto on @media (max-width: 767px) */
        iframe {
            width: 100% !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ url('resources/web/dist/assets/js/jquery-3.7.1.min.js') }}"></script>
    <!-- Internal Sweet-Alert js-->
    <script src="{{ asset('resources/spruha/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>

    <script
        src="{{ !config('services.midtrans.is_production') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script type="text/javascript">
        $('#pay-button').click(function(event) {
            event.preventDefault();

            $.post("{{ route('orders.pay-order') }}", {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                id: "{{ Crypt::encrypt($order->id) }}"
            }).done(function(data, status) {
                if (data.result === 'success') {
                    const purchaseData = data?.data;
                    if (!purchaseData) {
                        swal({
                            title: "Notifikasi",
                            text: "Gagal memperoleh informasi pembayaran",
                            type: 'error'
                        });
                        return;
                    }

                    // Record Analytics
                    analyticsInitiateCheckoutEvent({
                        transactionId: purchaseData.transaction_id,
                        totalPrice: purchaseData.total_price,
                        currency: purchaseData.currency,
                        totalTax: purchaseData.total_tax,
                        totalShipping: purchaseData.total_shipping,
                        coupon: purchaseData.coupon,
                        items: purchaseData.purchase_items,
                        userData: purchaseData.user_data,
                    });

                    const snapToken = purchaseData.snap_token;
                    if (!snapToken) {
                        swal({
                            title: "Notifikasi",
                            text: "Ada masalah saat memproses pembayaran.",
                            type: 'error'
                        });
                        return;
                    }
                    showSnapMidtrans(snapToken);
                } else {
                    swal({
                        title: "Notifikasi",
                        text: data.title,
                        type: data.result ?? 'error'
                    });
                }
            }).fail(function(error) {
                swal({
                    title: "Notifikasi",
                    text: error?.responseJSON?.title ?? error?.statusText ??
                        'Ada masalah saat memproses data.',
                    type: error?.responseJSON?.result ?? 'error'
                });
            });
        });

        function showSnapMidtrans(snapToken) {
            snap.pay(snapToken, {
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
        }
    </script>
@endsection
