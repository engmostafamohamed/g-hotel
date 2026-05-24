<?php

namespace App\DataTransferObjects\V1\CRM\Loyalty\LoyaltyAccountDTOs;

use App\Http\Requests\V1\CRM\Loyalty\LoyaltyAccount\StoreLoyaltyAccountRequest;
use App\Http\Requests\V1\CRM\Loyalty\LoyaltyAccount\UpdateLoyaltyAccountRequest;

class CreateLoyaltyAccountDTO
{

    public function __construct(
        public int $balance,
        public int $lifetime_earned,
        public int $lifetime_redeemed,
        public int $user_id,
        public int $tier_id,
    ) {}

    public static function fromRequest(StoreLoyaltyAccountRequest $request): self
    {
        return new self(
            balance: $request->input('balance'),
            lifetime_earned: $request->input('lifetime_earned'),
            lifetime_redeemed: $request->input('lifetime_redeemed'),
            user_id: $request->input('user_id'),
            tier_id: $request->input('tier_id'),
        );
    }
}
