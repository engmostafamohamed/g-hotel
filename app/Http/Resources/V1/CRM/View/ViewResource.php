<?php

namespace App\Http\Resources\V1\CRM\View;

use Illuminate\Http\Resources\Json\JsonResource;

class ViewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->localizedType,
        ];
    }
}
