<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'publish',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function hasilUjian(): BelongsTo
    {
        return $this->belongsTo(HasilUjian::class, 'hasil_ujian_id');
    }

    public function tryout(): BelongsTo
    {
        return $this->belongsTo(ProdukTryout::class, 'produk_tryout_id');
    }
}
