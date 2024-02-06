<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class PermissionRole extends Model
{
    use HasFactory,HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'permission_id',
        'role_id'
    ];

    protected $table = 'permissions_role';

}
