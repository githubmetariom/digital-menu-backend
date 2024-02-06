<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'display_name'
    ];


    public function usersRelation(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissionsRelation(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function scopeNameFilter($query, $names)
    {
        return $query->whereIn('name', $names);
    }

}
