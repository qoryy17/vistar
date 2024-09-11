<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        use App\Helpers\BerandaUI;
        $web = BerandaUI::web();
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="author" content="{{ $web->meta_author ? $web->meta_author : '' }}">
    <meta name="keywords" content="{{ $web->meta_keyword ? $web->meta_keyword : '' }}">
    <meta name="description" content="{{ $web->meta_description ? $web->meta_description : '' }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" type="image/png" />

    <!-- Title -->
    <title>@yield('title')</title>

    <!-- Bootstrap css-->
    <link id="style" href="{{ asset('resources/spruha/assets/plugins/bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet" />

    <!-- Icons css-->
    <link href="{{ asset('resources/spruha/assets/plugins/web-fonts/icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('resources/spruha/assets/plugins/web-fonts/font-awesome/font-awesome.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('resources/spruha/assets/plugins/web-fonts/plugin.css') }}" rel="stylesheet" />

    <!-- Style css-->
    <link href="{{ asset('resources/spruha/assets/css/style.css') }}" rel="stylesheet">

    <!-- InternalFileupload css-->
    <link href="{{ asset('resources/spruha/assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- DATA TABLE CSS -->
    <link href="{{ asset('resources/spruha/assets/plugins/datatable/css/dataTables.bootstrap5.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('resources/spruha/assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('resources/spruha/assets/plugins/datatable/css/responsive.bootstrap5.css') }}"
        rel="stylesheet" />

    <!-- Internal Sweet-Alert css-->
    <link href="{{ asset('resources/spruha/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">

    <!-- Select2 css -->
    <link href="{{ asset('resources/spruha/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!--Bootstrap-datepicker css-->
    <link rel="stylesheet"
        href="{{ asset('resources/spruha/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('resources/spruha/assets/css/vistar.css') }}">

    @yield('styles')
</head>

{{-- <body class="ltr main-body leftmenu"> --}}

<body class="ltr horizontalmenu">

    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ asset('resources/spruha/assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- End Loader -->
