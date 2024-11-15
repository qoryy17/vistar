<?php

namespace App\Exports;

use App\Models\Sertikom\OrderPelatihanSeminarModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderSertikomExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $kategori;

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(string $tanggalAwal, string $tanggalAkhir, string $kategori)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->kategori = $kategori;
    }

    public function query()
    {
        return OrderPelatihanSeminarModel::query()->select(
            'order_pelatihan_seminar.id',
            'order_pelatihan_seminar.customer_id',
            'order_pelatihan_seminar.produk_pelatihan_seminar_id',
            'produk_pelatihan_seminar.produk',
            'order_pelatihan_seminar.payment_id',
            'payment.ref_order_id',
            'order_pelatihan_seminar.nama',
            'payment.nominal',
            'payment.status_transaksi',
            'kategori_produk.judul',
            'order_pelatihan_seminar.created_at',
        )->leftJoin('payment', 'order_pelatihan_seminar.payment_id', '=', 'payment.id')
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $this->kategori)
            ->whereDate('order_pelatihan_seminar.created_at', '>=', $this->tanggalAwal)
            ->whereDate('order_pelatihan_seminar.created_at', '<=', $this->tanggalAkhir);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Customer ID',
            'Produk ID',
            'Produk',
            'Payment ID',
            'Ref Order ID',
            'Nama',
            'Nominal',
            'Status',
            'Kategori',
            'Created At',
        ];
    }
}
