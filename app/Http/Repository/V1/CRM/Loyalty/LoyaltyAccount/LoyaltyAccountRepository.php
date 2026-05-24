<?php
namespace App\Http\Repository\V1\CRM\Loyalty\LoyaltyAccount;
use Illuminate\Http\Request;
use App\Http\Resources\V1\CRM\Point\LogsResource;
use App\Contracts\V1\CRM\LoyaltyAccount\LoyaltyAccountRepositoryInterface;
use App\DataTransferObjects\Loyalty\LogsDTOs\LogsDTO;
use App\Models\LoyaltyAccount;
use Illuminate\Database\QueryException;
use Exception;
use App\Http\Requests\V1\CRM\Loyalty\LoyaltyAccount\StoreLoyaltyAccountRequest;
use App\DataTransferObjects\V1\CRM\Loyalty\LoyaltyAccountDTOs\CreateLoyaltyAccountDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\LoyaltyAccountDTOs\UpdateLoyaltyAccountDTO;
class LoyaltyAccountRepository implements LoyaltyAccountRepositoryInterface
{
    public function showAllLoyaltyAccountsRepository(Request $request)    {
        $query = LoyaltyAccount::latest();
        $perPage = $request->input('per_page', 10);


        $result = $query->paginate($perPage);

        if ($result->isEmpty()) {
            return ['status' => 'point_not_found'];
        }
        return [
            'status' => true,
            'data'    => $result
        ];
    }
    public function showLoyaltyAccountRepository(Request $request,int $id)    {

        $account = LoyaltyAccount::where('id',$id)->first() ;

        if (!$account) {
            return ['status' => 'account_not_found'];
        }
        return [
            'status' => true,
            'data'    => $account
        ];
    }
    public function storeLoyaltyAccountRepository(CreateLoyaltyAccountDTO $request)    {
        try {
            LoyaltyAccount::create([
                'balance' => $request->balance,
                'lifetime_earned' => $request->lifetime_earned,
                'lifetime_redeemed' => $request->lifetime_redeemed,
                'user_id' => $request->user_id,
                'tier_id' => $request->tier_id,
            ]);
            return [
                'status' => 'success',
                'data' => [],
            ];

        } catch (QueryException $e) {
            return ['status' => 'db_error', 'message' => $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function updateLoyaltyAccountRepository(int $id,UpdateLoyaltyAccountDTO $request)    {
        try {
            $record=LoyaltyAccount::find($id);

            if (!$record) {
                return ['status' => 'account_not_found'];
            }
            $updateData = [];

            if(!is_null($request->balance)){
                $updateData['balance']=$request->balance;
            }
            if(!is_null($request->lifetime_earned)){
                $updateData['lifetime_earned']=$request->lifetime_earned;
            }
            if(!is_null($request->lifetime_redeemed)){
                $updateData['lifetime_redeemed']=$request->lifetime_redeemed;
            }
            if(!is_null($request->tier_id)){
                $updateData['balance']=$request->tier_id;
            }

            if (!empty($updateData)) {
                $record->update($updateData);
            }
            return [
                'status' => 'success',
            ];

        } catch (QueryException $e) {
            return ['status' => 'db_error', 'message' => $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    public function deleteLoyaltyAccountRepository(Request $request,int $id){
        try {

            $record = LoyaltyAccount::where('id',$id)
                // ->where('id', $id)
                // ->whereNull('deleted_at')
                ->first();
            if (!$record) {
                return ['status' => 'account_not_found'];
            }
            $record->delete(); // Use soft delete

            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error'];
        }
    }

}
