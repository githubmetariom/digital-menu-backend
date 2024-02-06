<?php

namespace Modules\User\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'referral_id' => $this->referral_id,
            'referral_code' => $this->referral_code,
            'name' => $this->name,
            'family' => $this->family,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'national_code' => $this->national_code,
            'date_of_birth' => $this->date_of_birth,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
