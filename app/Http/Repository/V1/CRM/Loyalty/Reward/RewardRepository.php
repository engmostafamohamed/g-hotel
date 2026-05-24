<?php
namespace App\Http\Repository\V1\CRM\Loyalty\Reward;
use Illuminate\Http\Request;
// use App\Http\Resources\V1\CRM\Point\LogsResource;
use App\Contracts\V1\CRM\Loyalty\Reward\RewardRepositoryInterface;
// use App\DataTransferObjects\Loyalty\LogsDTOs\LogsDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\Reward\CreateRewardDTO;
use App\Models\Point;
use Illuminate\Database\QueryException;
use Exception;
use App\DataTransferObjects\V1\CRM\Loyalty\Reward\UpdateRewardDTO;
use App\Models\LoyaltyReward;
use GuzzleHttp\Promise\Create;

class RewardRepository implements RewardRepositoryInterface
{
    public function showRewardRepository($id)
    {
        $reward = LoyaltyReward::query()
            -> select('id', 'name', 'cost_points', 'sku', 'stock', 'active', 'meta')
            -> find($id);

        if (!$reward) {
            return ['status' => 'reward_not_found'];
        }
        return [
            'status' => true,
            'data'   => $reward
        ];
    }

    public function showRewardsRepository(Request $request)    {
        $query = LoyaltyReward::latest();
        $perPage = $request->input('per_page', 10);
        $rewards = $query->paginate($perPage);

        if ($rewards->isEmpty()) {
            return ['status' => 'rewards_not_found'];
        }
        return [
            'status' => true,
            'data'    => $rewards
        ];
    }

    public function storeRewardRepository(CreateRewardDTO $request)    {
        try {
            $reward = new LoyaltyReward();
            $reward->name = [
                'en' => $request->name['en'] ?? null,
                'ar' => $request->name['ar'] ?? null,
            ];
            // $reward->description = [
            //     'ar' => $request->description_ar,
            //     'en' => $request->description_en,
            // ];
            $reward->sku = $request->sku;
            $reward->active = $request->active;
            $reward->stock = $request->stock;
            $reward->cost_points = $request->cost_points;
            // $reward->meta = $request->meta;
            $reward->save();
            return [
                'status' => true,
                'message' => 'Reward created successfully',
                'data' => $reward
            ];
        } catch (QueryException $e) {
            // Handle database query exceptions
            return [
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            // Handle general exceptions
            return [
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    public function updateRewardRepository(UpdateRewardDTO $request, string $id)    {
        try {
            $reward = LoyaltyReward::find($id);
            if (!$reward) {
                return [
                    'status' => 'reward_not_found',
                ];
            }
            if (isset($request->sku)) {
                $reward->sku = $request->sku;
            }

            if (!empty($request->name)) {
                $reward->name = array_merge($reward->name ?? [], $request->name);
            }
            // if (!empty($request->description_ar)) {
            //     $reward->description = array_merge($reward->description ?? [], ['ar' => $request->description_ar]);
            // }
            // if (!empty( $request->description_en)) {
            //     $reward->description = array_merge($reward->description ?? [], ['en' => $request->description_en]);
            // }
            if (!empty($request->cost_points)) {
                $reward->cost_points = $request->cost_points;
            }
            if (!empty($request->stock)) {
                $reward->stock = $request->stock;
            }
            if (!empty($request->active)) {
                $reward->active = $request->active;
            }
            $reward->save();
            return [
                'status' => true,
                'data' => $reward
            ];
        } catch (QueryException $e) {
            // Handle database query exceptions
            return [
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            // Handle general exceptions
            return [
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    public function destroyRewardRepository(string $id)    {
        try {
            $reward = LoyaltyReward::find($id);
            if (!$reward) {
                return [
                    'status' => 'reward_not_found'
                ];
            }
            $reward->delete();

            return [
                'status' => true,
                'message' => 'Reward deleted successfully'
            ];
        } catch (QueryException $e) {
            // Handle database query exceptions
            return [
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            // Handle general exceptions
            return [
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
