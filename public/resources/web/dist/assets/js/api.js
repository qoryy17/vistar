
document.getElementById('tanggalLahir').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, ''); // Hanya izinkan angka
        if (value.length >= 2) value = value.slice(0, 2) + '/' + value.slice(2);
        if (value.length >= 5) value = value.slice(0, 5) + '/' + value.slice(5);
        e.target.value = value.slice(0, 10); // Batasi panjang maksimal
});

// var urlProvinsi = "https://ibnux.github.io/data-indonesia/provinsi.json";
// var urlKabupaten = "https://ibnux.github.io/data-indonesia/kabupaten/";
// var urlKecamatan = "https://ibnux.github.io/data-indonesia/kecamatan/";
// var urlKelurahan = "https://ibnux.github.io/data-indonesia/kelurahan/";

// function clearOptions(id) {
//     console.log("on clearOptions :" + id);
//     //$('#' + id).val(null);
//     $("#" + id)
//     .empty()
//     .trigger("change");
// }

// console.log("Load Provinsi...");
// $.getJSON(urlProvinsi, function (res) {
//      res = $.map(res, function (obj) {
//         obj.text = obj.nama;
//             return obj;
// });

// data = [
//     {
//         id: "",
//         nama: "- Pilih Provinsi -",
//         text: "- Pilih Provinsi -",
//     },
// ].concat(res);

// //implemen data ke select provinsi
// $("#select2-provinsi").select2({
//     dropdownAutoWidth: true,
//         width: "100%",
//         data: data,
//     });
// });

// var selectProv = $("#select2-provinsi");
// $(selectProv).change(function () {
//     var value = $(selectProv).val();
//     clearOptions("select2-kabupaten");

//     if (value) {
//         console.log("on change selectProv");

//         var text = $("#select2-provinsi :selected").text();
//         console.log("value = " + value + " / " + "text = " + text);

//         console.log("Load Kabupaten di " + text + "...");
//         $.getJSON(urlKabupaten + value + ".json", function (res) {
//             res = $.map(res, function (obj) {
//                 obj.text = obj.nama;
//                 return obj;
//         });

//         data = [
//             {
//                 id: "",
//                 nama: "- Pilih Kabupaten -",
//                 text: "- Pilih Kabupaten -",
//             },
//         ].concat(res);

//         //implemen data ke select provinsi
//         $("#select2-kabupaten").select2({
//             dropdownAutoWidth: true,
//             width: "100%",
//             data: data,
//             });
//         });
//     }
// });

// var selectKab = $("#select2-kabupaten");
// $(selectKab).change(function () {
//     var value = $(selectKab).val();
//     clearOptions("select2-kecamatan");

//     if (value) {
//         console.log("on change selectKab");

//         var text = $("#select2-kabupaten :selected").text();
//         console.log("value = " + value + " / " + "text = " + text);

//         console.log("Load Kecamatan di " + text + "...");
//         $.getJSON(urlKecamatan + value + ".json", function (res) {
//             res = $.map(res, function (obj) {
//                 obj.text = obj.nama;
//                     return obj;
//             });

//             data = [
//                 {
//                     id: "",
//                     nama: "- Pilih Kecamatan -",
//                     text: "- Pilih Kecamatan -",
//                 },
//             ].concat(res);

//             //implemen data ke select provinsi
//             $("#select2-kecamatan").select2({
//                 dropdownAutoWidth: true,
//                     width: "100%",
//                     data: data,
//             });
//         });
//     }
// });

// var selectKec = $("#select2-kecamatan");
// $(selectKec).change(function () {
//     var value = $(selectKec).val();
//     clearOptions("select2-kelurahan");

//     if (value) {
//         console.log("on change selectKec");

//         var text = $("#select2-kecamatan :selected").text();
//         console.log("value = " + value + " / " + "text = " + text);

//         console.log("Load Kelurahan di " + text + "...");
//         $.getJSON(urlKelurahan + value + ".json", function (res) {
//             res = $.map(res, function (obj) {
//                 obj.text = obj.nama;
//                 return obj;
//             });

//             data = [
//                 {
//                     id: "",
//                     nama: "- Pilih Kelurahan -",
//                     text: "- Pilih Kelurahan -",
//                 },
//             ].concat(res);

//             //implemen data ke select provinsi
//             $("#select2-kelurahan").select2({
//                 dropdownAutoWidth: true,
//                 width: "100%",
//                 data: data,
//             });
//         });
//     }
// });

// var selectKel = $("#select2-kelurahan");
// $(selectKel).change(function () {
// var value = $(selectKel).val();
//     if (value) {
//         console.log("on change selectKel");
//         var text = $("#select2-kelurahan :selected").text();
//         console.log("value = " + value + " / " + "text = " + text);
//     }
// });


// var selectedProvinsi = "{{ $customer->provinsi }}";  // ID provinsi dari tabel
// var selectedKabupaten = "{{ $customer->kabupaten }}";  // ID kabupaten dari tabel
// var selectedKecamatan = "{{ $customer->kecamatan }}";  // ID kecamatan dari tabel

// var urlProvinsi = "https://ibnux.github.io/data-indonesia/provinsi.json";
// var urlKabupaten = "https://ibnux.github.io/data-indonesia/kabupaten/";
// var urlKecamatan = "https://ibnux.github.io/data-indonesia/kecamatan/";
// var urlKelurahan = "https://ibnux.github.io/data-indonesia/kelurahan/";

// function clearOptions(id) {
//     console.log("on clearOptions :" + id);
//     $("#" + id)
//         .empty()
//         .trigger("change");
// }

// console.log("Load Provinsi...");
// $.getJSON(urlProvinsi, function (res) {
//     res = $.map(res, function (obj) {
//         obj.text = obj.nama;
//         return obj;
//     });

//     data = [{
//         id: "",
//         nama: "- Pilih Provinsi -",
//         text: "- Pilih Provinsi -",
//     }].concat(res);

//     $("#select2-provinsi").select2({
//         dropdownAutoWidth: true,
//         width: "100%",
//         data: data,
//     }).val(selectedProvinsi).trigger('change'); // Pre-select provinsi

//     loadKabupaten(selectedProvinsi); // Load kabupaten setelah provinsi terpilih
// });

// function loadKabupaten(provinsiID) {
//     clearOptions("select2-kabupaten");

//     if (provinsiID) {
//         console.log("Load Kabupaten di Provinsi " + provinsiID + "...");
//         $.getJSON(urlKabupaten + provinsiID + ".json", function (res) {
//             res = $.map(res, function (obj) {
//                 obj.text = obj.nama;
//                 return obj;
//             });

//             data = [{
//                 id: "",
//                 nama: "- Pilih Kabupaten -",
//                 text: "- Pilih Kabupaten -",
//             }].concat(res);

//             $("#select2-kabupaten").select2({
//                 dropdownAutoWidth: true,
//                 width: "100%",
//                 data: data,
//             }).val(selectedKabupaten).trigger('change'); // Pre-select kabupaten

//             loadKecamatan(selectedKabupaten); // Load kecamatan setelah kabupaten terpilih
//         });
//     }
// }

// function loadKecamatan(kabupatenID) {
//     clearOptions("select2-kecamatan");

//     if (kabupatenID) {
//         console.log("Load Kecamatan di Kabupaten " + kabupatenID + "...");
//         $.getJSON(urlKecamatan + kabupatenID + ".json", function (res) {
//             res = $.map(res, function (obj) {
//                 obj.text = obj.nama;
//                 return obj;
//             });

//             data = [{
//                 id: "",
//                 nama: "- Pilih Kecamatan -",
//                 text: "- Pilih Kecamatan -",
//             }].concat(res);

//             $("#select2-kecamatan").select2({
//                 dropdownAutoWidth: true,
//                 width: "100%",
//                 data: data,
//             }).val(selectedKecamatan).trigger('change'); // Pre-select kecamatan
//         });
//     }
// }

// // Event listener ketika provinsi berubah
// $("#select2-provinsi").change(function () {
//     var value = $(this).val();
//     loadKabupaten(value);
// });

// // Event listener ketika kabupaten berubah
// $("#select2-kabupaten").change(function () {
//     var value = $(this).val();
//     loadKecamatan(value);
// });

