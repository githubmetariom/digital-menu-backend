<?php

namespace Modules\Shop\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $supportedLanguages = Config::get('languages.supported');
        return [
            'store_id' => ['required', 'uuid', 'exists:stores,id'],
            'slug' => ['required', 'string'],
            'thumbnail' => ['nullable', 'file'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'lang' => ['nullable', 'in:' . implode(',', $supportedLanguages)],
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
