<?php

namespace App\Jobs;

use App\Mail\EmailFaktur;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendInvoiceJob implements ShouldQueue
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
        $orderDetail = DB::table('order_tryout')
            ->select(
                'order_tryout.*',
                'produk_tryout.nama_tryout',
                'produk_tryout.keterangan',
                'pengaturan_tryout.harga',
                'pengaturan_tryout.harga_promo',
                'pengaturan_tryout.masa_aktif',
                'payment.nominal'
            )
            ->leftJoin('produk_tryout', 'order_tryout.produk_tryout_id', '=', 'produk_tryout.id')
            ->leftJoin('pengaturan_tryout', 'produk_tryout.pengaturan_tryout_id', '=', 'pengaturan_tryout.id')
            ->leftJoin('payment', 'order_tryout.payment_id', '=', 'payment.id')
            ->where('order_tryout.id', '=', $this->orderInvoice['order_id'])
            ->first();

        // Customer
        $userEmail = User::where('customer_id', $orderDetail->customer_id)->first();

        // Kirim email invoice
        Mail::to($userEmail->email)->send(new EmailFaktur($orderDetail));
    }
}
