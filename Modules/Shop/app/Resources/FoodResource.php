<?php

namespace Modules\Shop\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'slug' => $this->slug,
            'category' => CategoryResource::make($this->categoryRelation),
            'depot' => $this->depot,
            'thumbnail' => $this->thumbnail,
            'status' => $this->status,
            'language' => LanguageResource::collection($this->languages)
        ];
    }
}
