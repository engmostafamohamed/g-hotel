<?php
namespace App\Contracts\V1\CRM\LoyaltyAccount;
use Illuminate\Http\Request;
use App\DataTransferObjects\V1\CRM\Loyalty\LoyaltyAccountDTOs\CreateLoyaltyAccountDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\LoyaltyAccountDTOs\UpdateLoyaltyAccountDTO;

interface LoyaltyAccountRepositoryInterface
{
    public function showAllLoyaltyAccountsRepository(Request $request);
    public function showLoyaltyAccountRepository(Request $request,int $id);
    public function storeLoyaltyAccountRepository(CreateLoyaltyAccountDTO $request);
    public function updateLoyaltyAccountRepository(int $id,UpdateLoyaltyAccountDTO $request) ;
    public function deleteLoyaltyAccountRepository(Request $request,int $id);
}
