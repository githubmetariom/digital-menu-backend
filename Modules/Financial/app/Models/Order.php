<?php

namespace Modules\Financial\app\Models;

use Modules\Shop\app\Models\Store;
use Modules\User\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'number'
    ];

    protected $table = 'orders';

    public function usersRelation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }

    public function invoicesRelation(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
