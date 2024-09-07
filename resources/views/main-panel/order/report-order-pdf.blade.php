@php
    $web = \App\Helpers\BerandaUI::web();
@endphp
 <!DOCTYPE html>
 <html lang="id">

 <head>
     <title>Laporan Rekapitulasi Order {{ $title }}</title>
     <style type="text/css">
         .table-header {
             width: 100%;
             margin-bottom: 10px;
         }

         .title {
             font-size: 14px;
             font-weight: bold;
         }

         .title-2 {
             font-size: 10px;
         }

         .title-cetak {
             font-size: 9px;
         }

         .table-data {
             width: 100%;
             border: 1px solid black;
             border-collapse: collapse;
             font-size: 10px;
         }
     </style>
 </head>

 <body>
     <table class="table-header">
         <tr>
             <td style="text-align: center"><img width="100px" src="{{ public_path('storage/' . $web->logo) }}"
                     alt="logo" /></td>
             <td>
                 <div class="title">{{ $web->nama_bisnis }}</div>
                 <span class="title-2">{{ $web->tagline }}</span><br />
                 <span class="title-2">{{ $web->alamat }}</span>
             </td>
         </tr>
         <tr>
             <td colspan="2" style="text-align: center">
                 <h6>Laporan Rekapitulasi Order {{ $title }}</h6>
             </td>
         </tr>
     </table>
     <table class="table-data" border="1" cellpadding="5px">
         <thead>
             <tr>
                 <th>No</th>
                 <th>Nomor ID</th>
                 <th>Informasi Pembelian</th>
                 <th>Created At</th>
             </tr>
         </thead>
         <tbody>
             @php
                 $no = 1;
             @endphp
             @foreach ($order as $row)
                 <tr>
                     <td>{{ $no }}</td>
                     <td>
                         Order ID : <strong>{{ $row->id }}</strong> <br>
                         Faktur ID : <strong>{{ $row->faktur_id }}</strong> <br>
                         Payment ID : <strong>{{ $row->payment_id }}</strong> <br>
                         Ref Order ID : <strong> {{ $row->ref_order_id }}</strong>
                     </td>
                     <td>
                         Nama Lengkap : <strong>{{ $row->nama }}</strong><br>
                         Produk Tryout : <strong>{{ $row->nama_tryout }}</strong> <br>
                         Harga : <strong>{{ Number::currency($row->nominal, in: 'IDR') }}</strong> <br>
                         Status <strong>{{ $row->status_transaksi }}</strong>
                     </td>
                     <td>{{ $row->created_at }}</td>
                 </tr>
                 @php
                     $no++;
                 @endphp
             @endforeach
         </tbody>
     </table>
     <p class="title-cetak">Waktu Cetak : {{ $waktuCetak }}</p>
 </body>

 </html>
