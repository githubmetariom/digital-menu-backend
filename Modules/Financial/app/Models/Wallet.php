<?php

namespace Modules\Financial\app\Models;

use Exception;
use Modules\User\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'amount'
    ];

    public function userRelation(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @throws Exception
     */
    public function deposit($amount)
    {
        if ($amount > 0) {
            $this->increment('amount', $amount);
        } else {
            throw new Exception("Deposit amount must be greater than zero");
        }
    }

    /**
     * @throws Exception
     */
    public function withdraw($amount)
    {
        if ($amount > 0 && $this->amount >= $amount) {
            $this->decrement('amount', $amount);
        } else {
            throw new Exception("Insufficient balance or withdrawal amount must be greater than zero");
        }
    }
}
