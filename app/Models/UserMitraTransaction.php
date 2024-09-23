<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMitraTransaction extends Model
{
    use HasFactory;

    protected $table = 'user_mitra_transactions';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id_mitra',
        'user_id_buyer',
        'transaction_id',
        'total_transaction',
        'total_income',
        'promotion_data',
    ];

    public static function getTableName(): string
    {
        $object = new UserMitraTransaction();
        return $object->getTable();
    }

    public function user_mitra(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_mitra');
    }

    public function user_buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_buyer');
    }
}
