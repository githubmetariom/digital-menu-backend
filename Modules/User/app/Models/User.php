<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Financial\app\Models\Wallet;
use Modules\Shop\app\Models\Store;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUlids;


    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'referral_id',
        'referral_code',
        'name',
        'family',
        'mobile',
        'email',
        'national_code',
        'date_of_birth',
        'thumbnail'
    ];

    protected $table = 'users';

    public function referrerRelation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referral_id');
    }

    public function storesRelation(): HasMany
    {
        return $this->hasMany(Store::class, 'user_id');
    }

    public function rolesRelation(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissionsRelation(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function scopeMobileFilter($query, $mobile)
    {
        return $query->where('mobile', $mobile);
    }

    public function permissions(): array
    {
        $userPermissions = [];
        foreach ($this->rolesRelation as $role) {
            foreach ($role->permissionsRelation as $permission) {
                $userPermissions[] = $permission->name;
            }
        }
        return array_unique($userPermissions);
    }

    public function walletRelation(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function getLinkAttribute(): string
    {
        return url('http://localhost') . '/' . '?ref=' . $this->referral_code;
    }

    public function scopeReferralCodeFilter($query, $code)
    {
        return $query->where('referral_code', $code);
    }
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referral_id');
    }
}
