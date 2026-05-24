<?php

namespace App\Http\Controllers\V1\CRM\Loyalty\Reward;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Repository\V1\CRM\Loyalty\Reward\RewardRepository;
use App\Http\Resources\V1\CRM\Loyalty\Reward\PaginatedRewardListResource;
use App\Http\Requests\V1\CRM\Loyalty\Reward\StoreRewardRequest;
use App\Http\Requests\V1\CRM\Loyalty\Reward\UpdateRewardRequest;
use App\DataTransferObjects\V1\CRM\Loyalty\Reward\CreateRewardDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\Reward\UpdateRewardDTO;
use GuzzleHttp\Promise\Create;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private RewardRepository $rewardRepository) {}
    public function index(Request $request)
    {
        $result= $this->rewardRepository->showRewardsRepository($request);
        if ($result['status'] === 'rewards_not_found') {
            return ApiResponse::error(__('loyalty/reward.reward_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('loyalty/reward.data_fetched_successfully'),
            new PaginatedRewardListResource($result['data']),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRewardRequest $request)
    {
        $dto = CreateRewardDTO::fromRequest($request);
        $result= $this->rewardRepository->storeRewardRepository($dto);
        // if ($result['status'] === 'reward_not_created') {
        //     return ApiResponse::error(__('loyalty.reward.reward_not_created'), [], 200);
        // }
        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('loyalty/tier.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('loyalty/reward.reward_created_successfully'),
            [],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result= $this->rewardRepository->showRewardRepository($id);
        if ($result['status'] === 'reward_not_found') {
            return ApiResponse::error(__('loyalty/reward.reward_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('loyalty/reward.data_fetched_successfully'),
            $result['data'],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRewardRequest $request, string $id)
    {
        $dto = UpdateRewardDTO::fromRequest($request);
        $result= $this->rewardRepository->updateRewardRepository($dto, $id);
        if ($result['status'] === 'reward_not_updated') {
            return ApiResponse::error(__('loyalty/reward.reward_not_updated'), [], 200);
        }
        if ($result['status'] === 'reward_not_found') {
            return ApiResponse::error(__('loyalty/reward.reward_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('loyalty/reward.reward_updated_successfully'),
            [],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result= $this->rewardRepository->destroyRewardRepository($id);
        if ($result['status'] === 'reward_not_deleted') {
            return ApiResponse::error(__('loyalty/reward.reward_not_deleted'), [], 200);
        }
        if ($result['status'] === 'reward_not_found') {
            return ApiResponse::error(__('loyalty/reward.reward_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('loyalty/reward.reward_deleted_successfully'),
            [],
            200
        );
    }
}
