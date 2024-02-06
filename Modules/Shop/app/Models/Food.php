<?php

namespace Modules\Shop\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Financial\app\Models\Invoice;

class Food extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'price',
        'category_id',
        'depot',
        'thumbnail',
        'status'
    ];
    protected $table = 'foods';

    public function invoicesRelation(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class);
    }

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function languages(): MorphMany
    {
        return $this->morphMany(Language::class, 'module');
    }

}
