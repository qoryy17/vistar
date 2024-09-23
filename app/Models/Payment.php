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
        'transaksi_id',

        'subtotal',
        'promo_type',
        'promo_code',
        'promo_data',
        'discount',

        'nominal',

        'metode',
        'biaya_admin',
        'status_transaksi',
        'status_fraud',
        'waktu_transaksi',
        'metadata',

    ];

    public $incrementing = false;

    public static $transactionStatus = [
        'pending' => [
            'title' => 'Menunggu Pembayaran',
            'color' => '#ffffff',
            'bg-color' => '#d7a701',
        ],
        'paid' => [
            'title' => 'Sudah Dibayar',
            'color' => '#ffffff',
            'bg-color' => '#27a168',
        ],
        'failed' => [
            'title' => 'Pembayaran Gagal',
            'color' => '#ffffff',
            'bg-color' => '#fd6074',
        ],
        'expired' => [
            'title' => 'Pembayaran Kadaluarsa',
            'color' => '#ffffff',
            'bg-color' => '#fd6074',
        ],
    ];

    public $timestamps = true;

    public function orderTryout(): BelongsTo
    {
        return $this->belongsTo(OrderTryout::class);
    }
}
