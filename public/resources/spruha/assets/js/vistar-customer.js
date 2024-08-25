$(function() {
	'use strict'

	$('#example1').DataTable({
      language: {
         searchPlaceholder: 'Search...',
         sSearch: '',
         lengthMenu: '_MENU_ items/page',
      }
   });

   //Date picker
    $("#datepicker-date").bootstrapdatepicker({
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
});