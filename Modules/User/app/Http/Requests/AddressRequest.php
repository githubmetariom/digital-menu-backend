<?php

namespace Modules\User\app\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Enumeration\AddressStatusEnum;

class AddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required', 'uuid', 'exists:users,id',
            'store_id' => 'nullable', 'uuid', 'exists:stores,id',
            'address' => ['required', 'string'],
            'location' => ['required', 'string'],
            'status' => ['required', Rule::exists('enumerations', 'id')
                ->where(function (Builder $query) {
                    return $query->where('parent_id', AddressStatusEnum::PARENT);
                })]
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
