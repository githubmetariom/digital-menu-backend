<?php

namespace Modules\Financial\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shop\app\Resources\FoodResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order' => OrderResource::make($this->orderRelation),
            'amount' => $this->amount,
            'discount' => $this->discount,
            'total' => $this->total,
            'amount_total' => $this->amount_total,
            'foods' => FoodResource::collection($this->foodsRelation)
        ];
    }
}
