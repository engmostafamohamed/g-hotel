<?php

namespace App\Http\Resources\V1\CRM\Loyalty\Reward;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RewardResource extends JsonResource
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
            'sku' => $this->sku,
            'reward_name' => [
                'ar' => $this->name['ar'] ?? null,
                'en' => $this->name['en'] ?? null,
            ],
            'cost_points' => $this->cost_points,
            // 'meta' => [
            //     'ar' => $this->meta['ar'] ?? null,
            //     'en' => $this->meta['en'] ?? null,
            // ],
            'stock' => $this->stock,
            'active' => $this->active,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
