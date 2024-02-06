<?php

namespace Modules\Shop\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Financial\app\Models\Order;
use Modules\User\app\Models\User;

class Store extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'slug',
        'thumbnail'
    ];

    protected $table = 'stores';

    public function usersRelation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function languages(): MorphMany
    {
        return $this->morphMany(Language::class, 'module');
    }

}
