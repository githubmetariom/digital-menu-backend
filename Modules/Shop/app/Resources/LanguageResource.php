<?php

namespace Modules\Shop\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'module_id' => $this->module_id,
            'module_type' => $this->module_type,
            'lang' => $this->lang,
            'key' => $this->key,
            'value' => $this->value
        ];
    }
}
