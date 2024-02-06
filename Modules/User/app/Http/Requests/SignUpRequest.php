<?php

namespace Modules\User\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\User\app\Models\Role;
use Modules\User\app\Rules\OtpVerifyRule;
use Modules\User\Enumeration\RolesEnum;

class SignUpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'name' => ['required', 'string'],
            'family' => ['required', 'string'],
            'mobile' => ['required', 'numeric', 'digits:11', 'unique:users'],
            'code' => ['required', 'numeric', new OtpVerifyRule(request()->mobile)],
            'email' => ['required', 'email', 'unique:users'],
            'national_code' => ['required', 'regex:/^\d{10}$/'],
            'date_of_birth' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'file'],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'in:'
                . RolesEnum::SUPERUSER . ',' .
                RolesEnum::BUYER . ',' .
                RolesEnum::SALES_REPRESENTATIVE . ',' .
                RolesEnum::SELLER],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
