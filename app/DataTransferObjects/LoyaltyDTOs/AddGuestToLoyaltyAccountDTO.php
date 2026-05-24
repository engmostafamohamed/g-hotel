<?php

namespace App\DataTransferObjects\LoyaltyDTOs;

use App\Http\Requests\V1\Api\Loyalty\AssignGuestToLoyaltyAccountRequest;

class AddGuestToLoyaltyAccountDTO
{
    public function __construct(
        
        public ?int $pointEarned,
        public ?int $pointRedeemed,
        public int $guest_id,
        public ?int $hotel_id,
        public ?int $tier_id,
    ) {}

    public static function fromRequest(AssignGuestToLoyaltyAccountRequest $request): self
    {
        return new self(
            pointEarned: $request->input('pointEarned'),
            pointRedeemed: $request->input('pointRedeemed'),
            guest_id: $request->input('guest_id'),
            hotel_id: $request->input('hotel_id'),
            tier_id: $request->input('tier_id'),
        );
    }

}
