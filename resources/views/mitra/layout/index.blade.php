@php
    use App\Helpers\BerandaUI;
    $web = BerandaUI::web();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
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

    <link rel="stylesheet" href="{{ asset('resources/spruha/assets/css/vistar.css') }}">

    @yield('styles')
</head>

<body class="ltr horizontalmenu">

    <div class="page">
        @include('mitra.layout.header')

        @yield('content')

        @include('mitra.layout.footer')
    </div>


    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ asset('resources/spruha/assets/img/loader.svg') }}" class="loader-img" alt="Loader"
            title="Loader" loading="eager">
    </div>
    <!-- End Loader -->

    <!-- Back-to-top -->
    <a href="#top" id="back-to-top" style="background-color: #0075B8;"><i class="fe fe-arrow-up"></i></a>

    <!-- Jquery js-->
    <script src="{{ asset('resources/spruha/assets/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('resources/spruha/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Internal Chartjs charts js-->
    <script src="{{ asset('resources/spruha/assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>

    <!-- Internal Fileuploads js-->
    <script src="{{ asset('resources/spruha/assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/fileuploads/js/file-upload.js') }}"></script>

    <!-- Perfect-scrollbar js -->
    <script src="{{ asset('resources/spruha/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <!-- Sidemenu js -->
    <script src="{{ asset('resources/spruha/assets/plugins/sidemenu/sidemenu.js') }}" id="leftmenu"></script>

    <!-- Sidebar js -->
    <script src="{{ asset('resources/spruha/assets/plugins/sidebar/sidebar.js') }}"></script>

    <!-- Internal Data Table js -->
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('resources/spruha/assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>

    <!-- Internal Sweet-Alert js-->
    <script src="{{ asset('resources/spruha/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>

    {{-- Vi Star Custom JS --}}
    <script src="{{ asset('resources/spruha/assets/js/vistar-customer.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ asset('resources/spruha/assets/js/themeColors.js') }}"></script>

    <!-- Sticky js -->
    <script src="{{ asset('resources/spruha/assets/js/sticky.js') }}"></script>

    <!-- Custom js -->
    <script src="{{ asset('resources/spruha/assets/js/custom.js') }}"></script>

    @if (session()->has('message'))
        <script>
            window.onload = function() {
                swal({
                    title: "Notifikasi",
                    text: " {{ session('message') }}",
                    type: "success"
                });
            }
        </script>
    @elseif (session()->has('error'))
        <script>
            window.onload = function() {
                swal({
                    title: "Notifikasi",
                    text: " {{ session('error') }}",
                    type: "error"
                });
            }
        </script>
    @endif

    <script>
        function copyToClipboard(formId) {
            // Get the text field
            var copyText = document.getElementById(formId);

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);
        }
    </script>

    @yield('scripts')

</body>

</html>
