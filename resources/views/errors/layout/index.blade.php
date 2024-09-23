@php
    use App\Helpers\BerandaUI;
    $web = BerandaUI::web();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

</head>

<body class="ltr main-body leftmenu error-1">
    <!-- Page -->
    <div class="page main-signin-wrapper bg-primary construction">

        @yield('content')

    </div>
    <!-- End Page -->

    <!-- Jquery js-->
    <script src="{{ asset('resources/spruha/assets/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('resources/spruha/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Perfect-scrollbar js -->
    <script src="{{ asset('resources/spruha/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ asset('resources/spruha/assets/js/themeColors.js') }}"></script>

    <!-- Custom js -->
    <script src="{{ asset('resources/spruha/assets/js/custom.js') }}"></script>

</body>

</html>
