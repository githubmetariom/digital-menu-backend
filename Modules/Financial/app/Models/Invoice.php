<?php

namespace Modules\Financial\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Shop\app\Models\Food;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'amount',
        'discount',
        'total',
        'amount_total'
    ];

    public function orderRelation(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function foodsRelation(): BelongsToMany
    {
        return $this->belongsToMany(Food::class);
    }

    public function scopeOrderIdFilter($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }
}
