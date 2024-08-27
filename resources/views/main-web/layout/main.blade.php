@include('main-web.layout.header')
<x-web.landing-navbar />
{{-- <x-Landingnavbar /> --}}
<!-- Content Website -->
@yield('content')
<!-- End Content Website -->

<x-web.landing-footer />

@include('main-web.layout.footer')
