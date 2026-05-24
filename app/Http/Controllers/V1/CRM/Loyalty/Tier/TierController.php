<?php

namespace App\Http\Controllers\V1\CRM\Loyalty\Tier;
use App\Http\Repository\V1\CRM\Loyalty\Tier\TierRepository;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Tier;
use Illuminate\Http\Request;
use App\Http\Requests\V1\CRM\Loyalty\Tier\StoreTierRequest;
use App\Http\Requests\V1\CRM\Loyalty\Tier\UpdateTierRequest;
use App\Http\Requests\V1\CRM\Loyalty\Tier\TierRequest;
use App\DataTransferObjects\V1\CRM\Loyalty\TierDTOs\UpdateTierDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\TierDTOs\CreateTierDTO;
use App\Http\Resources\V1\CRM\Loyalty\Tier\TierResource;
use App\Http\Resources\V1\CRM\Loyalty\Tier\PaginatedTierListResource;

class TierController extends Controller
{
    protected $Tier;
    public function __construct(private TierRepository $tier) {}
    public function showAllTiers(Request  $request ){
        $result= $this->tier->showTiersRepository($request);
        if ($result['status'] === 'tier_not_found') {
            return ApiResponse::error(__('loyalty/tier.tier_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('loyalty/tier.data_fetched_successfully'),
            new PaginatedTierListResource($result['tiers']),
            200
        );
    }
    public function showTier(Request $request ,int $id){
        $result= $this->tier->showTierRepository($request,$id);
        if ($result['status'] === 'tier_not_found') {
            return ApiResponse::error(__('loyalty/tier.tier_id_not_found'), [], 200);
        }

        return ApiResponse::success(
            __('loyalty/tier.data_fetched_successfully'),
            new TierResource($result['tier']),
            200
        );
    }
    public function addTier(StoreTierRequest $request){
        $result= $this->tier->storeTierRepository(CreateTierDTO::fromRequest($request));
        // if ($result['status'] === 'image_not_found') {
        //     return ApiResponse::error(__('loyalty.tier.image_not_found'), [], 200);
        // }

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('loyalty/tier.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('loyalty/tier.data_added_successfully'),
            [],
            201
        );
    }
    public function updateTier(UpdateTierRequest $request,int $id){
        $result= $this->tier->updateTierRepository($id, UpdateTierDTO::fromRequest($request));
        if($result['status'] === 'tier_not_found'){
            return ApiResponse::error(
                __('loyalty/tier.tier_id_not_found'),
                [],
                200
            );
        }
        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('loyalty/tier.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('loyalty/tier.data_updated_successfully'),
            [],
            200
        );
    }
    public function DeleteTier(Request $request,int $id){
        $result= $this->tier->deleteTierRepository( $request , $id);
        if($result['status'] === 'tier_not_found'){
            return ApiResponse::error(
                __('loyalty/tier.tier_id_not_found'),
                [],
                200
            );
        }
        return ApiResponse::success(
            __('loyalty/tier.data_deleted_successfully'),
            [],
            200
        );
    }
}
