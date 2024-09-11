<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'payment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'customer_id',
        'ref_order_id',
        'snap_token',
        'status_transaksi',


        'status_order',
        'status_pesan',
        'nominal',
        'batas_pembayaran',
        'jenis_pembayaran',
        'biaya_admin',
        'status',
    ];

    public $incrementing = false;

    public static $transactionStatus = [
        'pending' => [
            'title' => 'Menunggu Pembayaran',
            'color' => '#ffffff',
            'bg-color' => '#d7a701'
        ],
        'paid' => [
            'title' => 'Sudah Dibayar',
            'color' => '#ffffff',
            'bg-color' => '#27a168'
        ],
        'failed' => [
            'title' => 'Pembayaran Gagal',
            'color' => '#ffffff',
            'bg-color' => '#fd6074'
        ],
        'expired' => [
            'title' => 'Pembayaran Kadaluarsa',
            'color' => '#ffffff',
            'bg-color' => '#fd6074'
        ],
    ];

    public $timestamps = true;

    public function orderTryout(): BelongsTo
    {
        return $this->belongsTo(OrderTryout::class);
    }
}
