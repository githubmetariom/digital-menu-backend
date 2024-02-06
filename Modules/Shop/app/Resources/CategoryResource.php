<?php

namespace Modules\Shop\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'store' => StoreResource::make($this->storeRelation),
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'language' => LanguageResource::collection($this->languages)
        ];
    }
}
