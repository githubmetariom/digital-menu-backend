<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Shop\app\Models\Store;


class Address extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'address',
        'location',
        'status',
    ];
    protected $table = 'addresses';

    public function userRelation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function storeRelation(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

}
