<?php

namespace Modules\Shop\app\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Modules\Shop\Enumeration\FoodStatusEnum;

class FoodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $supportedLanguages = Config::get('languages.supported');
        return [
            'slug' => ['required', 'string'],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'price' => ['required', 'integer'],
            'depot' => ['required', 'integer'],
            'thumbnail' => ['nullable', 'file'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'lang' => ['nullable', 'in:' . implode(',', $supportedLanguages)],
            'status' => ['required', Rule::exists('enumerations', 'id')
                ->where(function (Builder $query) {
                    return $query->where('parent_id', FoodStatusEnum::PARENT);
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
