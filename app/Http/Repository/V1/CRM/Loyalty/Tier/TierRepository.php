<?php
namespace App\Http\Repository\V1\CRM\Loyalty\Tier;
use Illuminate\Http\Request;
use App\Http\Resources\V1\CRM\Loyalty\Tier\TierResource;
use App\Contracts\Loyalty\Tier\TierRepositoryInterface;
use App\DataTransferObjects\V1\CRM\Loyalty\TierDTOs\UpdateTierDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\TierDTOs\CreateTierDTO;
use App\Models\LoyaltyTier;
use Illuminate\Database\QueryException;
use Exception;

class TierRepository implements TierRepositoryInterface
{
    public function showTiersRepository(Request $request){
        $hotelId = $request->input('hotel_id');
        // $query = Tier::when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))->paginate(10);
        $perPage = $request->input('per_page', 10); // default = 10

        // $query = LoyaltyTier::with("services")
            // ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
        //     ->paginate($perPage);

        $query = LoyaltyTier::paginate($perPage);

        if ($query->isEmpty()) {
            return ['status' => 'tier_not_found'];
        }
        return [
            'status' => 'success',
            'tiers' => $query,
        ];
    }
    public function showTierRepository(Request $request, int $id)
    {
        // $hotelId = $request->input('hotel_id');

        $query = LoyaltyTier::where('id', $id)
                    // ->whereNull('deleted_at')
                    // ->with('services')
                    ->first();

        if (!$query) {
            return ['status' => 'tier_not_found'];
        }

        return [
            'status' => 'success',
            'tier' => $query,
        ];
    }
    public function storeTierRepository(CreateTierDTO $request) {
        try {
            // Create tier
            $record = LoyaltyTier::create([
                'tier_name' => [
                    'en' => $request->tier_name['en'] ?? null,
                    'ar' => $request->tier_name['ar'] ?? null,
                ],
                'code' => $request->code,
                'threshold' => $request->threshold,
                'content' => [
                    'en' => $request->content['en'] ?? null,
                    'ar' => $request->content['ar'] ?? null,
                ],
            ]);

            // Assign tier to services
            // if (!empty($request->service_ids)) {
            //     $record->services()->attach($request->service_ids);
            // }
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
    public function updateTierRepository(int $id, UpdateTierDTO $request)
    {
        try {
            $record = LoyaltyTier::find($id);
            if (!$record) {
                return ['status' => 'tier_not_found'];
            }

            $updateData = [];
            // Check tier_name
            if (!empty($request->tier_name)) {
                $updateData['tier_name'] = [
                    'en' => $request->tier_name['en'] ?? $record->getTranslation('tier_name', 'en'),
                    'ar' => $request->tier_name['ar'] ?? $record->getTranslation('tier_name', 'ar'),
                ];
            }

            if (!is_null($request->code)) {
                $updateData['code'] = $request->code;
            }

            if (!is_null($request->threshold)) {
                $updateData['threshold'] = $request->threshold;
            }


            // if (!empty($request->service_ids) ) {
            //     $record->services()->sync($request->service_ids);
            // }
            if (!empty($request->content)) {
                $updateData['content'] = [
                    'en' => $request->content['en'] ?? $record->getTranslation('content', 'en'),
                    'ar' => $request->content['ar'] ?? $record->getTranslation('content', 'ar'),
                ];
            }

            if (!empty($updateData)) {
                $record->update($updateData);
            }
            // dd($record);
            return [
                'status' => 'success',
                // 'tier' => $record->fresh('services'),
            ];
        } catch (QueryException $e) {
            return ['status' => 'db_error', 'message' => $e->getMessage()];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

    }

    public function deleteTierRepository(Request $request,int $id){
        try {
            $hotelId = $request->input('hotel_id');

            // Find Tier by ID and hotel_id
            $record = LoyaltyTier::where('id',$id)
                // ->where('id', $id)
                // ->whereNull('deleted_at')
                ->first();
            if (!$record) {
                return ['status' => 'tier_not_found'];
            }
            $record->delete(); // Use soft delete

            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error'];
        }
    }
}
