<?php

namespace Modules\User\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\app\Http\Requests\UserRequest;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->userRelation),
//            'shop'=>StoreResource::make($this->storeRelation),
            'address' => $this->address,
            'location' => $this->location,
            'status' => $this->status
        ];
    }
}
