@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Start -->
    <section class="section" style="margin-top: 50px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h1 class="fs-3 title mb-4">Pembayaran</h1>
                        <p class="text-muted mb-0 mx-auto">
                            Berikut informasi detail pesanan anda, periksa terlebih dahulu sebelum melakukan pembayaran,
                            jika informasi sudah benar silahkan klik tombol <strong>Bayar Sekarang</strong> untuk melakukan
                            proses pembayaran
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            @foreach ($orders as $order)
                <div class="row">
                    <div class="col-12">
                        {{--  SEO Purpose  --}}
                        <h2 class="hide fs-5">Data Pembelian</h2>
                        <div class="table-responsive bg-white shadow rounded">
                            <table class="table mb-0 table-center">
                                <thead>
                                    <tr>
                                        <th class="border-bottom text-start" style="min-width: 300px;">Workshop</th>
                                        <th class="border-bottom text-start" style="min-width: 150px;">Benefit</th>
                                        <th class="border-bottom text-end" style="min-width: 150px;">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="shop-list">
                                        <td class="text-start" style="vertical-align: top;">
                                            <div class="align-items-center">
                                                <h3 class="fs-6">{{ $order->produk }}</h3>
                                                <div class="fs-6 d-inline">
                                                    <span>Topik :</span>
                                                    <h4 class="fs-6 fw-normal d-inline">
                                                        {{ $order->topik }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <h5 class="mt-3 fs-6">Jadwal Workshop</h5>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $order->tanggal_mulai)->format('d/m/Y') }}
                                            -
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $order->tanggal_selesai)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-start" style="vertical-align: top;">
                                            <h5 class="fs-6">Benefit Workshop</h5>
                                            @php
                                                $benefit = App\Helpers\BerandaUI::benefitWorkshop();
                                            @endphp
                                            <ul class="list-unstyled">
                                                @foreach ($benefit as $listBenefit)
                                                    <li class="mb-0">
                                                        <span class="icon me-2">
                                                            <i class="uil uil-check-circle align-middle"></i>
                                                        </span>
                                                        {{ $listBenefit }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-end">
                                            <span class="h6">Rp. {{ number_format($order->harga, 0) }}</span>
                                        </td>
                                    </tr>
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
                                        <th id="total-label" class="h6 ps-4 py-3">
                                            <h2 class="fs-6">Total</h2>
                                        </th>
                                        <th id="total-container" class="text-end fw-bold pe-4">
                                            <h3 id="total-data" class="fs-6">Rp. {{ number_format($order->harga, 0) }}
                                            </h3>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="mt-4 pt-2 text-end">
                            <a href="{{ route('mainweb.cart-sertikom', ['category' => 'pelatihan']) }}"
                                class="btn btn-pills btn-soft-warning">
                                <i class="mdi mdi-reply"></i> Kembali
                            </a>
                            <button id="pay-button" type="submit" class="btn btn-pills btn-soft-primary">
                                Bayar Sekarang <i class="mdi mdi-arrow-right"></i>
                            </button>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            @endforeach
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->
@endsection

@section('styles')
    <style>
        /* Fix style.css, why iframe set to auto on @media (max-width: 767px) */
        iframe {
            width: 100% !important;
        }
    </style>
@endsection
@section('scripts')
    <script
        src="{{ !config('services.midtrans.is_production') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script type="text/javascript">
        $('#pay-button').click(function(event) {
            event.preventDefault();

            const requestParameter = {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                id: "{{ Crypt::encrypt($order->id) }}",
                category: 'workshop'
            }

            $.post("{{ route('orders.sertikom-pay-order') }}", requestParameter)
                .done(function(data, status) {
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
                    window.location.href =
                        "{{ route('site.pembelian-sertikom', ['category' => 'workshop']) }}";
                },
                onPending: function(result) {
                    window.location.href =
                        "{{ route('site.pembelian-sertikom', ['category' => 'workshop']) }}";
                },
                onError: function(result) {
                    window.location.href =
                        "{{ route('site.pembelian-sertikom', ['category' => 'workshop']) }}";
                }
            });
        }
    </script>
@endsection
