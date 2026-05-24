<?php

namespace App\Http\Controllers\V1\Api\Loyalty;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\Loyalty\AssignGuestToLoyaltyAccountRequest;
use App\Http\Requests\V1\Api\Loyalty\LoyaltyTransactionRequest;
use App\Http\Requests\V1\Api\Loyalty\RedeemRewardRequest;
use App\Services\V1\Api\Loyalty\LoyaltyService;
use App\DataTransferObjects\LoyaltyDTOs\AddGuestToLoyaltyAccountDTO;
use App\DataTransferObjects\LoyaltyDTOs\RedeemRewardDTO;
use App\DataTransferObjects\LoyaltyDTOs\LoyaltyTransactionDTO;
class LoyaltyController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyServices)
    {
        $this->loyaltyService = $loyaltyServices;
    }
    public function assignGuestToLoyaltyAccount(AssignGuestToLoyaltyAccountRequest $request)
    {
        $result = $this->loyaltyService->addGuestToLoyaltyAccount(AddGuestToLoyaltyAccountDTO::fromRequest($request));
        // the retuen like this

        if ($result['message'] === 'guest_not_found') {
            return ApiResponse::error(__('loyalty/transaction.data_not_found'), [], 200);
        }
        if ($result['message'] === 'hotel_not_found') {
            return ApiResponse::error(__('loyalty/transaction.hotel_id_not_found'), [], 200);
        }

        return ApiResponse::success(
            __('loyalty/transaction.data_fetched_successfully'),
                [],
            200
        );
    }
    public function loyaltyTransaction(LoyaltyTransactionRequest $request)
    {
      $result = $this->loyaltyService->loyaltyTransaction(LoyaltyTransactionDTO::fromRequest($request));

        if ('error' === $result['status']) {
            return ApiResponse::error($result['message'], [], 400);
        }

        return ApiResponse::success(
            __('loyalty/redeemReward.loyaltyTransaction_successfully'),
                [],
            200
        );
    }
    public function redeemReward(RedeemRewardRequest $request)
    {
        $result = $this->loyaltyService->redeemReward(RedeemRewardDTO::fromRequest($request));

        if ('error' === $result['status']) {
            return ApiResponse::error($result['message'], [], 400);
        }

        return ApiResponse::success(
            __('loyalty/redeemReward.reward_redeemed_successfully'),
                [],
            200
        );
    }

}
