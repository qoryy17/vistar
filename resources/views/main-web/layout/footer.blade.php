<!-- Back to top -->
<a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5">
    <i data-feather="arrow-up" class="fea icon-sm icons align-middle"></i>
</a>
<!-- Back to top -->

<!-- JAVASCRIPT -->
<script src="{{ asset('resources/web/dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('resources/web/dist/assets/js/jquery-3.7.1.min.js') }}"></script>

@yield('scripts-top')

<!-- Animation -->
<script src="{{ asset('resources/web/dist/assets/libs/wow.js/wow.min.js') }}"></script>
<!-- Parallax -->
<script src="{{ asset('resources/web/dist/assets/libs/jarallax/jarallax.min.js') }}"></script>
<!-- Main Js -->
<script src="{{ asset('resources/web/dist/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('resources/web/dist/assets/js/plugins.init.js') }}"></script>
<script src="{{ asset('resources/web/dist/assets/js/app.js') }}"></script>
<!-- Internal Sweet-Alert js-->
<script src="{{ url('resources/spruha/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>

@if (session()->has('success'))
    <script>
        window.onload = function() {
            swal({
                title: "Notifikasi",
                text: " {{ session('success') }}",
                type: "success"
            });
        }
    </script>
@endif
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
@endif
@if (session()->has('error'))
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

{{--  Analytics  --}}
{{--  Google tag (gtag.js)  --}}
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytic.google.id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    $(document).ready(function() {
        gtag('js', new Date());
        gtag('config', "{{ config('services.analytic.google.id') }}");
    });
</script>

<!-- Meta Pixel Code -->
<script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');

    $(document).ready(function() {
        fbq('init', "{{ config('services.analytic.facebook.id') }}");
        fbq('track', 'PageView');
    });
</script>
<noscript><img alt="" height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ config('services.analytic.facebook.id') }}&ev=PageView&noscript=1" /></noscript>
<!-- End Meta Pixel Code -->

<script src="{{ asset('resources/web/dist/assets/js/analytics.js') }}"></script>

<script>
    function number_format(number, options) {
        const locale = 'id';
        const formatter = new Intl.NumberFormat(locale, options);

        return formatter.format(number)
    }
</script>
@yield('scripts')

</body>

</html>
