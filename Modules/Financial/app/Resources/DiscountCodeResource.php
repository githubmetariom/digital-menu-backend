<?php

namespace Modules\Financial\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\app\Resources\UserResource;

class DiscountCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'code' => $this->code,
            'discount' => $this->discount,
            'type' => $this->type,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'max_uses' => $this->max_uses,
            'user' => UserResource::make($this->userRelation),
        ];
    }
}
