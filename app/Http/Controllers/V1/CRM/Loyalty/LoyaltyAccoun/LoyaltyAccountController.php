<?php

namespace App\Http\Controllers\V1\CRM\Loyalty\LoyaltyAccount;
use App\Http\Repository\V1\CRM\Loyalty\LoyaltyAccount\LoyaltyAccountRepository;
use App\Helpers\ApiResponse;
use App\Http\Resources\V1\CRM\Loyalty\LoyaltyAccount\PaginatedLoyaltyAccountListResource;
use App\Http\Resources\V1\CRM\Loyalty\LoyaltyAccount\LoyaltyAccountResource;
use App\DataTransferObjects\V1\CRM\Loyalty\LoyaltyAccountDTOs\CreateLoyaltyAccountDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\LoyaltyAccountDTOs\UpdateLoyaltyAccountDTO;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Loyalty\LoyaltyAccount\StoreLoyaltyAccountRequest;
use App\Http\Requests\V1\CRM\Loyalty\LoyaltyAccount\UpdateLoyaltyAccountRequest;
use App\Models\LoyaltyAccount;
use Illuminate\Http\Request;

class LoyaltyAccountController extends Controller
{

    public function __construct(private LoyaltyAccountRepository $loyaltyAccount){}
    public function showAllAccounts(Request $request)
    {
        $result =$this->loyaltyAccount->showAllLoyaltyAccountsRepository($request);
        if($result['status'] === 'account_not_found'){
            return ApiResponse::error(__('loyalty/loyaltyAccount.account_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('loyalty/loyaltyAccount.data_fetched_successfully'),
            new PaginatedLoyaltyAccountListResource($result['account']),
            200
        );
    }

    public function showLoyaltyAccount(Request $request , int $id){
        $result =$this->loyaltyAccount->showLoyaltyAccountRepository($request,$id);
        if ($result['status'] === 'account_not_found') {
            return ApiResponse::error(__('loyalty/loyaltyAccount.account_id_not_found'), [], 200);
        }

        return ApiResponse::success(
            __('loyalty/loyaltyAccount.data_fetched_successfully'),
            new LoyaltyAccountResource($result['account']),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addAccount(StoreLoyaltyAccountRequest $request)
    {
        $result =$this->loyaltyAccount->storeLoyaltyAccountRepository(CreateLoyaltyAccountDTO::fromRequest($request));

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('loyalty/LoyaltyAccount.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('loyalty/LoyaltyAccount.data_added_successfully'),
            [],
            201
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAccount(UpdateLoyaltyAccountRequest $request, string $id)
    {
        $result =$this->loyaltyAccount->updateLoyaltyAccountRepository($id,UpdateLoyaltyAccountDTO::fromRequest($request));

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('loyalty/LoyaltyAccount.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('loyalty/LoyaltyAccount.data_updated_successfully'),
            [],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAccount(Request $request,int $id)
    {
        $result= $this->loyaltyAccount->deleteLoyaltyAccountRepository( $request , $id);
        if($result['status'] === 'loyaltyAccount_not_found'){
            return ApiResponse::error(
                __('loyalty/LoyaltyAccount.loyaltyAccount_id_not_found'),
                [],
                200
            );
        }
        return ApiResponse::success(
            __('loyalty/LoyaltyAccount.data_deleted_successfully'),
            [],
            200
        );
    }
}
