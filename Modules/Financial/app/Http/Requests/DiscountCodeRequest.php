<?php

namespace Modules\Financial\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:discount_codes,code'],
            'discount' => ['required', 'integer'],
            'type' => ['required', 'in:fixed,percentage'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date'],
            'users' => ['required', 'array'],
            'users.*.id' => ['required', 'uuid', 'exists:users,id'],
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
