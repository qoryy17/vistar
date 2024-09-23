<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMitra extends Model
{
    use HasFactory;

    protected $table = 'users_mitra';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'promotion_code',
        'balances',
        'user_benefit_type',
        'user_benefit_value',
        'buyer_benefit_type',
        'buyer_benefit_value',
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'company_logo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
