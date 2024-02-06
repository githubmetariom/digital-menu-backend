<?php

namespace Modules\Financial\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Financial\app\Rules\ValidateDiscountCodeRule;

class InvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'uuid', 'exists:orders,id'],
            'foods' => ['required', 'array'],
            'foods.*' => ['required', 'uuid', 'exists:foods,id'],
            'discount_code' => ['required', 'exists:discount_codes,code', new ValidateDiscountCodeRule()]
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
