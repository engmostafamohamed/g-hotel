<?php

namespace App\Http\Resources\V1\CRM\Loyalty\LoyaltyAccount;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyAccountResource extends JsonResource
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
            'user_id' => $this->user_id,
            'points' => $this->points,
            'tier_id' => $this->tier_id,
            'balance' => $this->balance,
            'lifetime_earned' => $this->lifetime_earned,
            'lifetime_redeemed' => $this->lifetime_redeemed ,
        ];
    }
}
