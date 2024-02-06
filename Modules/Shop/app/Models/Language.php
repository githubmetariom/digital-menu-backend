<?php

namespace Modules\Shop\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Language extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'module_id',
        'module_type',
        'lang',
        'key',
        'value'
    ];

    public function module(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeLangFilter($query, $lang)
    {
        return $query->where('lang', $lang);
    }

}
