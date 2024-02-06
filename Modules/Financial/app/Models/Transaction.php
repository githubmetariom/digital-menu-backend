<?php

namespace Modules\Financial\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Shop\app\Models\Store;
use Modules\User\app\Models\User;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
//        'store_id',
        'order_id',
        'amount',
        'type',
        'status'
    ];

    protected $table = 'transactions';

    public function userRelation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function storeRelation(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function orderRelation(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function scopeUserIdFilter($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

}
