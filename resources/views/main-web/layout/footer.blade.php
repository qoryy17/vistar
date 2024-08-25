<!-- Back to top -->
<a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up"
        class="fea icon-sm icons align-middle"></i></a>
<!-- Back to top -->

<!-- JAVASCRIPT -->
<script src="{{ url('resources/web/dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('resources/web/dist/assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ url('resources/web/dist/assets/js/select2.min.js') }}"></script>
<!-- Lightbox -->
<script src="{{ url('resources/web/dist/assets/libs/tobii/js/tobii.min.js') }}"></script>
<!-- Slider -->
<script src="{{ url('resources/web/dist/assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
<script src="{{ url('resources/web/dist/assets/libs/swiper/js/swiper.min.js') }}"></script>
<!-- Parallax -->
<script src="{{ url('resources/web/dist/assets/libs/jarallax/jarallax.min.js') }}"></script>
<!-- Main Js -->
<script src="{{ url('resources/web/dist/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ url('resources/web/dist/assets/js/plugins.init.js') }}"></script>
<script src="{{ url('resources/web/dist/assets/js/api.js') }}"></script>
<script src="{{ url('resources/web/dist/assets/js/app.js') }}"></script>

<script type="text/javascript">
    var selectedProvinsi = "{{ $customer->provinsi ?? null }}"; // ID provinsi dari tabel
    var selectedKabupaten = "{{ $customer->kabupaten ?? null }}"; // ID kabupaten dari tabel
    var selectedKecamatan = "{{ $customer->kecamatan ?? null }}"; // ID kecamatan dari tabel

    var urlProvinsi = "https://ibnux.github.io/data-indonesia/provinsi.json";
    var urlKabupaten = "https://ibnux.github.io/data-indonesia/kabupaten/";
    var urlKecamatan = "https://ibnux.github.io/data-indonesia/kecamatan/";
    var urlKelurahan = "https://ibnux.github.io/data-indonesia/kelurahan/";

    function clearOptions(id) {
        console.log("on clearOptions :" + id);
        $("#" + id)
            .empty()
            .trigger("change");
    }

    console.log("Load Provinsi...");
    $.getJSON(urlProvinsi, function(res) {
        res = $.map(res, function(obj) {
            obj.text = obj.nama;
            return obj;
        });

        data = [{
            id: "",
            nama: "- Pilih Provinsi -",
            text: "- Pilih Provinsi -",
        }].concat(res);

        $("#select2-provinsi").select2({
            dropdownAutoWidth: true,
            width: "100%",
            data: data,
        }).val(selectedProvinsi).trigger('change'); // Pre-select provinsi

        loadKabupaten(selectedProvinsi); // Load kabupaten setelah provinsi terpilih
    });

    function loadKabupaten(provinsiID) {
        clearOptions("select2-kabupaten");

        if (provinsiID) {
            console.log("Load Kabupaten di Provinsi " + provinsiID + "...");
            $.getJSON(urlKabupaten + provinsiID + ".json", function(res) {
                res = $.map(res, function(obj) {
                    obj.text = obj.nama;
                    return obj;
                });

                data = [{
                    id: "",
                    nama: "- Pilih Kabupaten -",
                    text: "- Pilih Kabupaten -",
                }].concat(res);

                $("#select2-kabupaten").select2({
                    dropdownAutoWidth: true,
                    width: "100%",
                    data: data,
                }).val(selectedKabupaten).trigger('change'); // Pre-select kabupaten

                loadKecamatan(selectedKabupaten); // Load kecamatan setelah kabupaten terpilih
            });
        }
    }

    function loadKecamatan(kabupatenID) {
        clearOptions("select2-kecamatan");

        if (kabupatenID) {
            console.log("Load Kecamatan di Kabupaten " + kabupatenID + "...");
            $.getJSON(urlKecamatan + kabupatenID + ".json", function(res) {
                res = $.map(res, function(obj) {
                    obj.text = obj.nama;
                    return obj;
                });

                data = [{
                    id: "",
                    nama: "- Pilih Kecamatan -",
                    text: "- Pilih Kecamatan -",
                }].concat(res);

                $("#select2-kecamatan").select2({
                    dropdownAutoWidth: true,
                    width: "100%",
                    data: data,
                }).val(selectedKecamatan).trigger('change'); // Pre-select kecamatan
            });
        }
    }

    // Event listener ketika provinsi berubah
    $("#select2-provinsi").change(function() {
        var value = $(this).val();
        loadKabupaten(value);
    });

    // Event listener ketika kabupaten berubah
    $("#select2-kabupaten").change(function() {
        var value = $(this).val();
        loadKecamatan(value);
    });
</script>
</body>

</html>
