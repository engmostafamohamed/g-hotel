<?php

namespace App\Http\Resources\V1\CRM\StaticPage;

use Illuminate\Http\Resources\Json\JsonResource;

class StaticPageSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->getTranslation('title', app()->getLocale()),
            'slug' => $this->slug,
            'is_active' => $this->is_active,
        ];
    }
}
