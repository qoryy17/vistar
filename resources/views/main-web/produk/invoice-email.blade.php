@php
    $web = \App\Helpers\BerandaUI::web();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="author" content="{{ $web->meta_author }}">
    <meta name="keywords" content="{{ $web->meta_keyword }}">
    <meta name="description" content="{{ $web->meta_description }}">

    <!-- Favicon -->
    <link rel="icon" type="image shortcut" href="{{ asset('favicon.ico') }}" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
</head>

<body style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400; color: #161c2d;">


    <!-- Hero Start -->
    <div style="margin-top: 50px;">
        <table cellpadding="0" cellspacing="0"
            style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400; max-width: 600px; border: none; margin: 0 auto; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
            <thead>
                <tr
                    style="background-color: #2f55d4; padding: 3px 0; text-align: center; color: #fff; font-size: 24px; letter-spacing: 1px;">
                    <th scope="col">
                        <h5>{{ $web->nama_bisnis }} <br><small style="font-size: 8px;">{{ $web->tagline }}</small></h5>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="padding: 24px 24px 0;">
                        <table cellpadding="0" cellspacing="0" style="border: none;">
                            <tbody>
                                <tr>
                                    <td colspan="2"
                                        style="min-width: 130px; padding-bottom: 8px; text-align: center;">Terimakasih
                                        telah
                                        melakukan pembelian, <br> berikut faktur pembelian anda :</td>
                                </tr>
                                <tr>
                                    <td style="min-width: 130px; padding-bottom: 8px;">Faktur ID. :</td>
                                    <td style="color: #8492a6;">{{ $orderDetail->faktur_id }}</td>
                                </tr>
                                <tr>
                                    <td style="min-width: 130px; padding-bottom: 8px;">Order ID. :</td>
                                    <td style="color: #8492a6;">{{ $orderDetail->id }}</td>
                                </tr>
                                <tr>
                                    <td style="min-width: 130px; padding-bottom: 8px;">Nama :</td>
                                    <td style="color: #8492a6;">{{ $orderDetail->nama }}</td>
                                </tr>
                                <tr>
                                    <td style="min-width: 130px; padding-bottom: 8px;">Waktu :</td>
                                    <td style="color: #8492a6;">{{ $orderDetail->created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 24px;">
                        <div
                            style="display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 6px; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col"
                                            style="text-align: left; vertical-align: bottom; border-top: 1px solid #dee2e6; padding: 12px; width: 200px;">
                                            Item</th>
                                        <th scope="col"
                                            style="text-align: end; vertical-align: bottom; border-top: 1px solid #dee2e6; padding: 12px;">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: left; padding: 12px; border-top: 1px solid #dee2e6;">
                                            {{ $orderDetail->nama_tryout }}
                                        </td>
                                        <td style="text-align: end; padding: 12px; border-top: 1px solid #dee2e6;">
                                            {{ is_numeric($orderDetail->nominal) ? Number::currency($orderDetail->nominal, in: 'IDR') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:center;">
                                            Keterangan : {{ $orderDetail->keterangan }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <small>
                            {{ $web->nama_bisnis }} {{ $web->tagline }}<br>
                            Ujian Tryout untuk CPNS, PPPK dan Kedinasan Terpercaya Seluruh Indonesia.
                        </small>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%">
                            <tr style="font-size: 10px;">
                                <td style="text-align: center">Follow Instagram Kami <a
                                        href="{{ $web->facebook }}">{{ $web->nama_bisnis }}</a>
                                </td>
                                <td style="text-align: center">Email Kami <a
                                        href="{{ $web->email }}">{{ $web->email }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> {{ $web->nama_bisnis }}.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Hero End -->
</body>

</html>
