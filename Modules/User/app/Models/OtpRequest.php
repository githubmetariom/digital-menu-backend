<?php

namespace Modules\User\app\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class OtpRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'mobile',
        'code',
        'ip',
        'is_verify'
    ];

    public function scopeVerifiedCode($query, $mobile, $code)
    {
        return $query->where('code', $code)
            ->where('mobile', $mobile)
            ->where('is_verify', false)
            ->where('created_at', '>=', Carbon::now()->subMinutes(Config::get('user.otpExpireTime')));
    }

}
