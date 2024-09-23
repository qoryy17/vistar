@include('main-web.layout.header')
<x-web.landing-navbar />

<!-- Content Website -->
@yield('content')
<!-- End Content Website -->

<x-web.landing-footer />

@include('main-web.layout.footer')
