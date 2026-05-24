<?php

namespace App\Http\Resources\V1\Api\StaticPages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaticPagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->getTranslation('title',app()->getLocale()),
            'content' => $this->getTranslation('content',app()->getLocale()),
            // 'title' => $this->getTranslations('title'),
            // 'content' => $this->getTranslations('content'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
