const urlProvinsi = "https://ibnux.github.io/data-indonesia/provinsi.json";
const urlKabupaten = "https://ibnux.github.io/data-indonesia/kabupaten/";
const urlKecamatan = "https://ibnux.github.io/data-indonesia/kecamatan/";
const urlKelurahan = "https://ibnux.github.io/data-indonesia/kelurahan/";

let selectFormIdProvinsi = null;
let selectFormIdKabupaten = null;
let selectFormIdKecamatan = null;

let selectedProvinsi = null;
let selectedKabupaten = null;
let selectedKecamatan = null;

let emptyMessageProvinsi = null;
let emptyMessageKabupaten = null;
let emptyMessageKecamatan = null;

function initGeolocation({
    formIdProvinsi,
    formIdKabupaten,
    formIdKecamatan,
    provinsiId = null,
    kabupatenId = null,
    kecamatanId = null,

    emptyMessageProvinsiProps = "- Pilih Provinsi -",
    emptyMessageKabupatenProps = "- Pilih Kabupaten -",
    emptyMessageKecamatanProps = "- Pilih Kecamatan -",
}) {
    selectFormIdProvinsi = formIdProvinsi;
    selectFormIdKabupaten = formIdKabupaten;
    selectFormIdKecamatan = formIdKecamatan;

    selectedProvinsi = provinsiId;
    selectedKabupaten = kabupatenId;
    selectedKecamatan = kecamatanId;

    emptyMessageProvinsi = emptyMessageProvinsiProps;
    emptyMessageKabupaten = emptyMessageKabupatenProps;
    emptyMessageKecamatan = emptyMessageKecamatanProps;

    // Event listener ketika provinsi berubah
    $("#" + formIdProvinsi).change(function () {
        var value = $(this).val();
        loadKabupaten(value);
    });

    // Event listener ketika kabupaten berubah
    $("#" + formIdKabupaten).change(function () {
        var value = $(this).val();
        loadKecamatan(value);
    });

    loadProvinsi(selectedProvinsi);
}

function loadProvinsi(selectedProvinsi) {
    $.getJSON(urlProvinsi, function (res) {
        res = $.map(res, function (obj) {
            obj.text = obj.nama;
            return obj;
        });

        data = [
            {
                id: "",
                nama: emptyMessageProvinsi,
                text: emptyMessageProvinsi,
            },
        ].concat(res);

        $("#" + selectFormIdProvinsi)
            .select2({
                dropdownAutoWidth: true,
                width: "100%",
                data: data,
            })
            .val(selectedProvinsi)
            .trigger("change"); // Pre-select provinsi

        loadKabupaten(selectedProvinsi); // Load kabupaten setelah provinsi terpilih
    });
}

function loadKabupaten(selectedProvinsi) {
    clearOptions(selectFormIdKabupaten);

    if (selectedProvinsi) {
        $.getJSON(urlKabupaten + selectedProvinsi + ".json", function (res) {
            res = $.map(res, function (obj) {
                obj.text = obj.nama;
                return obj;
            });

            data = [
                {
                    id: "",
                    nama: emptyMessageKabupaten,
                    text: emptyMessageKabupaten,
                },
            ].concat(res);

            $("#" + selectFormIdKabupaten)
                .select2({
                    dropdownAutoWidth: true,
                    width: "100%",
                    data: data,
                })
                .val(selectedKabupaten)
                .trigger("change"); // Pre-select kabupaten

            loadKecamatan(selectedKabupaten); // Load kecamatan setelah kabupaten terpilih
        });
    }
}

function loadKecamatan(selectedKabupaten) {
    clearOptions(selectFormIdKecamatan);

    if (selectedKabupaten) {
        $.getJSON(urlKecamatan + selectedKabupaten + ".json", function (res) {
            res = $.map(res, function (obj) {
                obj.text = obj.nama;
                return obj;
            });

            data = [
                {
                    id: "",
                    nama: emptyMessageKecamatan,
                    text: emptyMessageKecamatan,
                },
            ].concat(res);

            $("#" + selectFormIdKecamatan)
                .select2({
                    dropdownAutoWidth: true,
                    width: "100%",
                    data: data,
                })
                .val(selectedKecamatan)
                .trigger("change"); // Pre-select kecamatan
        });
    }
}

function clearOptions(id) {
    $("#" + id)
        .empty()
        .trigger("change");
}
