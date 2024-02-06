<?php

namespace Modules\General\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\General\Database\factories\EnumerationFactory;

class Enumeration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['title', 'parent_id'];

    protected $table = 'enumerations';

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Enumeration::class, 'parent_id');
    }

    public function scopeParentId($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }
}
