<?php

namespace App\Exports;

use App\Models\OrderTryout;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $tanggalAwal;
    protected $tanggalAkhir;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(string $tanggalAwal, string $tanggalAkhir)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function query()
    {
        return OrderTryout::query()->select(
            'order_tryout.id',
            'order_tryout.customer_id',
            'order_tryout.produk_tryout_id',
            'produk_tryout.nama_tryout',
            'order_tryout.payment_id',
            'payment.ref_order_id',
            'order_tryout.nama',
            'payment.nominal',
            'payment.snap_token',
            'order_tryout.created_at',
        )->leftJoin('payment', 'order_tryout.payment_id', '=', 'payment.id')
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->whereDate('order_tryout.created_at', '>=', $this->tanggalAwal)
            ->whereDate('order_tryout.created_at', '<=', $this->tanggalAkhir);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Customer ID',
            'Produk Tryout ID',
            'Nama Tryout',
            'Payment ID',
            'Ref Order ID',
            'Nama',
            'Nominal',
            'Snap Token',
            'Created At',
        ];
    }
}
