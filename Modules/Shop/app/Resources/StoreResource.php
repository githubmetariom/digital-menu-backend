<?php

namespace Modules\Shop\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\app\Resources\UserResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->usersRelation),
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail,
            'language' => LanguageResource::collection($this->languages)
        ];
    }
}
