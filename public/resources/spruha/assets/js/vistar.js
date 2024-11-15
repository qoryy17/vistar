$(function () {
    "use strict";

    $("#example1").DataTable({
        language: {
            searchPlaceholder: "Search...",
            Search: "",
            lengthMenu: "_MENU_ items/page",
        },
    });

    //Date picker
    $("#datepicker-date").bootstrapdatepicker({
        format: "dd-mm-yyyy",
        viewMode: "date",
        multidate: false,
        multidateSeparator: "-",
        autoclose: true,
    });

    $("#datepicker-date1").bootstrapdatepicker({
        format: "dd-mm-yyyy",
        viewMode: "date",
        multidate: false,
        multidateSeparator: "-",
        autoclose: true,
    });

    $("#datepicker-date2").bootstrapdatepicker({
        format: "dd-mm-yyyy",
        viewMode: "date",
        multidate: false,
        multidateSeparator: "-",
        autoclose: true,
    });

    $("#datepicker-date3").bootstrapdatepicker({
        format: "dd-mm-yyyy",
        viewMode: "date",
        multidate: false,
        multidateSeparator: "-",
        autoclose: true,
    });

    $('#timeStart').mask('99:99');

    $('#timeEnd').mask('99:99');

    $(".selectPendidikan").select2({
        placeholder: "Pilih Pendidikan",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectJurusan").select2({
        placeholder: "Pilih Jurusan/Program Studi",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectProvinsi").select2({
        placeholder: "Pilih Provinsi",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectKotaKab").select2({
        placeholder: "Pilih Kota/Kabupaten",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectKecamatan").select2({
        placeholder: "Pilih Kecamatan",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectTahun").select2({
        placeholder: "Pilih Tahun",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectProduk").select2({
        placeholder: "Pilih Tahun",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectKlasifikasi").select2({
        placeholder: "Pilih Klasifikasi",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectProduct").select2({
        placeholder: "Pilih Produk Tryout",
        searchInputPlaceholder: "Search",
        width: "100%",
    });
    
    $(".selectCustomer").select2({
        placeholder: "Pilih Customer",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectInstructor").select2({
        placeholder: "Pilih Instruktur",
        searchInputPlaceholder: "Search",
        width: "100%",
    });

    $(".selectExpertise").select2({
        placeholder: "Pilih Topik Keahlian",
        searchInputPlaceholder: "Search",
        width: "100%",
    });
});
