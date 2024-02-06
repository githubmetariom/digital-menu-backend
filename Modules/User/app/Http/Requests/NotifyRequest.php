<?php

namespace Modules\User\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Enumeration\NotifyTypeEnum;

class NotifyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'uuids' => ['required', 'array'],
            'uuids.*.id' => ['required', 'uuid', 'exists:users,id'],
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
            'type' => ['required', 'in:'
                . NotifyTypeEnum::SMS . ',' .
                NotifyTypeEnum::EMAIL
            ]
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
