<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimoni';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'customer_id',
        'produk_tryout_id',
        'hasil_ujian_id',
        'testimoni',
        'rating',
        'publish'
    ];

    public $incrementing = false;

    public $timestamps = true;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function hasilUjian(): BelongsTo
    {
        return $this->belongsTo(HasilUjian::class);
    }

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(ProdukTryout::class);
    }
}
