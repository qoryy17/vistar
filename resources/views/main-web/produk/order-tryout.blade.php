@php
    $promoCode = \App\Http\Controllers\PromoCodeController::getPromoCode();

    $total = 0;
@endphp
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
                                    <td class="text-start fw-bold pe-4" colspan="2">
                                        <div class="d-flex gap-2 align-items-start">
                                            <input name="promo-code" type="hidden" />
                                            <div class="flex-1">
                                                <input name="input-promo-code" type="text" class="form-control"
                                                    placeholder="Masukan Kode Promo"
                                                    value="{{ $promoCode ? $promoCode['code'] : '' }}" />
                                                <small class="hide" style="font-size: 0.8em;"
                                                    id="promo-code-message"></small>
                                            </div>
                                            <button id="button-use-promo-code" onclick="usePromoCode()"
                                                class="btn btn-pills btn-soft-primary text-nowrap">
                                                Pakai Kode
                                            </button>
                                            <button id="button-cancel-promo-code" onclick="cancelPromoCode()"
                                                class="btn btn-pills btn-soft-primary text-nowrap hide">
                                                &times;
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <th id="subtotal-label" class="h6 ps-4 py-3">
                                        <h2 class="fs-6" id=>Subtotal</h2>
                                    </th>
                                    <th id="subtotal-container" class="text-end fw-bold pe-4">
                                        <h3 id="subtotal-data" class="fs-6" data-total="{{ $total }}">
                                            Rp. {{ number_format($total, 0) }}
                                        </h3>
                                    </th>
                                </tr>
                                <tr class="bg-light">
                                    <th id="discount-label" class="h6 ps-4 py-3">
                                        <h2 class="fs-6">Diskon</h2>
                                    </th>
                                    <th id="discount-container" class="text-end fw-bold pe-4">
                                        <h3 id="discount-data" class="fs-6">0</h3>
                                    </th>
                                </tr>
                                <tr class="bg-light">
                                    <th id="total-label" class="h6 ps-4 py-3">
                                        <h2 class="fs-6">Total</h2>
                                    </th>
                                    <th id="total-container" class="text-end fw-bold pe-4">
                                        <h3 id="total-data" class="fs-6">0</h3>
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
        let promoData = null;
        let autoHideTimeout = null;

        function usePromoCode() {
            const promoCode = $('[name="input-promo-code"]').val()
            if (!promoCode || promoCode === '') {
                swal({
                    title: 'Silahkan masukkan Kode Promo',
                    'type': 'info'
                })
                return
            }

            checkPromoCode(promoCode)
        }

        function showPromoCodeMesage(message, type) {
            $('#promo-code-message').html(message);
            if (type === 'success') {
                $('#promo-code-message').addClass('text-success');
                $('#promo-code-message').removeClass('text-warning');
            } else if (type === 'error') {
                $('#promo-code-message').removeClass('text-success');
                $('#promo-code-message').addClass('text-warning');
            } else {
                $('#promo-code-message').removeClass('text-success');
                $('#promo-code-message').removeClass('text-warning');
            }

            $('#promo-code-message').fadeIn();
            autoHideTimeout = setTimeout(function() {
                $(`#promo-code-message`).fadeOut();
            }, 5000)
        }

        function cancelPromoCode() {
            $('[name="promo-code"]').val('');
            $('[name="input-promo-code"]').val('');
            $('[name="input-promo-code"]').attr('disabled', false);

            document.getElementById('button-use-promo-code').classList.remove('hide');
            document.getElementById('button-cancel-promo-code').classList.add('hide');

            promoData = null;

            updatePricing();
        }

        function checkPromoCode(promoCode) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('promo-code.check') }}",
                type: 'POST',
                data: {
                    promo_code: promoCode
                },
                success: function(response) {
                    if (response.result === 'success') {
                        showPromoCodeMesage(response.title, 'success')

                        promoData = response.data;

                        document.getElementById('button-use-promo-code').classList.add('hide');
                        document.getElementById('button-cancel-promo-code').classList.remove('hide');

                        $('[name="promo-code"]').val(promoCode);
                        $('[name="input-promo-code"]').attr('disabled', true);
                    } else {
                        promoData = null;
                        showPromoCodeMesage(response.title, 'error')
                    }
                },
                error: function(xhr) {
                    let errorMessage = "Mengecek Kode Promo gagal dikirim !";

                    const responseJson = xhr?.responseJSON
                    if (responseJson) {
                        if (responseJson.errors) {
                            errorMessage = '';
                            for (let errors of Object.values(responseJson.errors)) {
                                for (let error of errors) {
                                    errorMessage += error + '<br />'
                                }
                            }
                        } else if (responseJson.title) {
                            errorMessage = responseJson.title;
                        }
                    }
                    promoData = null;
                    showPromoCodeMesage(errorMessage, 'error')
                },
                complete: function() {
                    updatePricing();
                }
            });
        }

        function updatePricing() {
            const subTotal = parseFloat($('#subtotal-data').attr('data-total'));
            let discount = 0;
            let total = subTotal;
            if (promoData) {
                const promo = promoData.promo;
                if (promo.type === 'percent') {
                    discount = subTotal * promo.value / 100
                } else if (promo.type === 'deduction') {
                    discount = promo.value
                }
                total = subTotal - discount;
            }

            const options = {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            };
            $('#subtotal-data').html(number_format(subTotal, options));
            $('#discount-data').html(number_format(discount, options));
            $('#total-data').html(number_format(total, options));

        }

        $(document).ready(function() {
            const promoCode = $('[name="input-promo-code"]').val()
            if (promoCode && promoCode !== '') {
                checkPromoCode(promoCode)
            }
        });

        $('#pay-button').click(function(event) {
            event.preventDefault();

            const requestParameter = {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                id: "{{ Crypt::encrypt($order->id) }}",
                promo_code: $('[name="promo-code"]').val()
            }

            $.post("{{ route('orders.pay-order') }}", requestParameter)
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
