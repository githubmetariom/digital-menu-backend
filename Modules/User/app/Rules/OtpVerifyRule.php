<?php

namespace Modules\User\app\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\app\Models\OtpRequest;


class OtpVerifyRule implements ValidationRule
{
    private $mobile;

    public function __construct($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = OtpRequest::verifiedCode($this->mobile, (int)$value)
            ->exists();
        if ($exists == false) {
            $fail('The entered code is incorrect');
        }
    }
}
