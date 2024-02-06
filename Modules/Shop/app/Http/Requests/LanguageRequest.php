<?php

namespace Modules\Shop\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class LanguageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $supportedLanguages = Config::get('languages.supported');
        return [
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
