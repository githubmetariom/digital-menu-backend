<?php

namespace Modules\User\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\User\Enumeration\RolesEnum;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'referral_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string'],
            'family' => ['required', 'string'],
            'mobile' => ['required', 'numeric', 'digits:11', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'national_code' => ['required', 'regex:/^\d{10}$/'],
            'date_of_birth' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'file']
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
