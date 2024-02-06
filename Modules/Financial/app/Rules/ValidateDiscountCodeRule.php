<?php

namespace Modules\Financial\app\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Modules\Financial\app\Models\DiscountCode;

class ValidateDiscountCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $discountCodeQuery = DiscountCode::where('code', $value)
            ->where('start_at', '<', Carbon::now());

        if ($discountCodeQuery->exists()) {
            $discountCode = $discountCodeQuery->first();

            if ($discountCode->end_at && $discountCode->end_at < Carbon::now()) {
                $fail('The entered code has expired');
            }

            if ($discountCode->user_id && $discountCode->user_id != Auth::id()) {
                $fail('The entered code is not for this user');
            }
        } else {
            $fail('The entered code is incorrect');
        }
    }
}
