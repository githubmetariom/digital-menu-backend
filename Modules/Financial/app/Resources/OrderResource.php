<?php

namespace Modules\Financial\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shop\app\Resources\StoreResource;
use Modules\User\app\Resources\UserResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->usersRelation),
            'store' => StoreResource::collection($this->stores),
            'number' => $this->number
        ];
    }
}
