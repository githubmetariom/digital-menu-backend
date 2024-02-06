<?php

namespace Modules\Financial\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\app\Models\User;

class DiscountCode extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'discount',
        'type',
        'start_at',
        'end_at',
        'user_id',
    ];

    protected $table = 'discount_codes';

    public function userRelation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCodeFilter($query, $code)
    {
        return $query->where('code', $code);
    }

}
