@include('main-web.layout.header')
<x-web.landing-navbar />
{{-- <x-Landingnavbar /> --}}
<!-- Content Website -->
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
@yield('content')
<!-- End Content Website -->

<x-web.landing-footer />

@include('main-web.layout.footer')
