<?php

namespace Modules\Financial\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Financial\Enumeration\PaymentTypeEnumeration;

class TransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'order_id' => ['required', 'uuid', 'exists:orders,id'],
            'payment_type' => ['required', Rule::in([PaymentTypeEnumeration::GATEWAY, PaymentTypeEnumeration::WALLET])]
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
