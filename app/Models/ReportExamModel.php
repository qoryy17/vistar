<?php

namespace App\Models;

use App\Enums\ReportExamStatus;
use App\Http\Controllers\Panel\Users;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportExamModel extends Model
{
    use HasFactory;

    protected $table = 'report_ujian';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'produk_tryout_id',
        'soal_id',
        'deskripsi',
        'screenshot',
        'status',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public static function getStatusList()
    {
        return [
            ReportExamStatus::WAITING->value => 'Menunggu',
            ReportExamStatus::FIXED->value => 'Telah diperbaiki',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function produkTryout(): BelongsTo
    {
        return $this->belongsTo(ProdukTryout::class, 'produk_tryout_id');
    }

    public function soalUjian(): BelongsTo
    {
        return $this->belongsTo(SoalUjian::class, 'soal_id');
    }
}
