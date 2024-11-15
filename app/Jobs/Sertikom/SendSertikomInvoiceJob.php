<?php

namespace App\Jobs\Sertikom;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Sertikom\EmailSertikomFaktur;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSertikomInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    protected $orderInvoice;

    /**
     * Create a new job instance.
     */
    public function __construct(array $orderInvoice)
    {
        $this->orderInvoice = $orderInvoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Detail orderan buat email
        $orderDetail = DB::table('order_pelatihan_seminar')
            ->select(
                'order_pelatihan_seminar.*',
                'produk_pelatihan_seminar.id as idProduk',
                'produk_pelatihan_seminar.produk',
                'produk_pelatihan_seminar.harga',
                'produk_pelatihan_seminar.deskripsi',
                'produk_pelatihan_seminar.tanggal_mulai',
                'produk_pelatihan_seminar.tanggal_selesai',
                'topik_keahlian.topik',
                'payment.nominal',
                'kategori_produk.judul',
                'kategori_produk.status',
            )
            ->leftJoin('produk_pelatihan_seminar', 'order_pelatihan_seminar.produk_pelatihan_seminar_id', '=', 'produk_pelatihan_seminar.id')
            ->leftJoin('topik_keahlian', 'produk_pelatihan_seminar.topik_keahlian_id', '=', 'topik_keahlian.id')
            ->leftJoin('payment', 'order_pelatihan_seminar.payment_id', '=', 'payment.id')
            ->leftJoin('kategori_produk', 'produk_pelatihan_seminar.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('kategori_produk.judul', $this->orderInvoice['category'])
            ->whereNot('kategori_produk.status', 'Gratis')
            ->where('order_pelatihan_seminar.id', '=', $this->orderInvoice['order_id'])
            ->first();
        // Customer
        $userEmail = User::where('customer_id', $orderDetail->customer_id)->first();

        // Kirim email invoice
        Mail::to($userEmail->email)->send(new EmailSertikomFaktur($orderDetail));
    }
}
