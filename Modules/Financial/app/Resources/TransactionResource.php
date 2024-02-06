<?php

namespace Modules\Financial\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\app\Resources\UserResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->userRelation),
            'order' => OrderResource::make($this->orderRelation),
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status
        ];
    }
}
