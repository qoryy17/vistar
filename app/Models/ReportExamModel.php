<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportExamModel extends Model
{
    use HasFactory;

    protected $table = 'report_ujian';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'produk_tryout_id',
        'soal_id',
        'deskripsi',
        'screenshot',
        'status'
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function produkTryout(): BelongsTo
    {
        return $this->belongsTo(ProdukTryout::class, 'produk_tryout_id');
    }

    public function soalUjian(): BelongsTo
    {
        return $this->belongsTo(SoalUjian::class, 'soal_id');
    }
}
