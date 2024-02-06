<?php

namespace Modules\Shop\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Category extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'store_id',
        'thumbnail'
    ];

    protected $table = 'categories';

    public function storeRelation(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function languages(): MorphMany
    {
        return $this->morphMany(Language::class, 'module');
    }
}
