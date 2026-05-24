<?php

namespace App\Services\V1\Api\Loyalty;
use App\DataTransferObjects\LoyaltyDTOs\LoyaltyTransactionDTO;
use App\DataTransferObjects\LoyaltyDTOs\RedeemRewardDTO;

use App\Http\Repository\V1\Api\Loyalty\LoyaltyRepository;
class LoyaltyService
{
    protected $loyaltyRepository;
    public function __construct(LoyaltyRepository $loyaltyRepository)
    {
        $this->loyaltyRepository = $loyaltyRepository;
    }

    public function addGuestToLoyaltyAccount( $request)
    {
        $guestId = $request->guest_id;
        $pointEarned = $request->point_earned ?? 0;
        $pointRedeemed = $request->point_redeemed ?? 0;
        $hotelId = $request->hotel_id;
        $tier_id = $request->tier_id ?? null;
        return $this->loyaltyRepository->addGuestToLoyaltyAccountRepository($guestId, $tier_id,$pointEarned, $pointRedeemed);
    }
    public function loyaltyTransaction(LoyaltyTransactionDTO $dto): mixed
    {
      return $this->loyaltyRepository->addTransaction($dto);
    }
    public function redeemReward(RedeemRewardDTO $dto): mixed
    {
      return $this->loyaltyRepository->redeemReward($dto);
    }
}
