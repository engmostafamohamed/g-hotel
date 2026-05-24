<?php

namespace App\Http\Repository\V1\CRM\BlackoutDate;
use App\Contracts\BlackoutDate\BlackoutDateRepositoryInterface;
use App\Models\BlackoutDate;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;
use App\Http\Resources\V1\CRM\BlackoutDate\BlackoutDateResource;
use App\DataTransferObjects\BlackoutDateDTOs\BlackoutDateDTO;
use Illuminate\Support\Facades\DB;
class BlackoutDateRepository implements BlackoutDateRepositoryInterface
{
    public function showBlackoutDatesRepository(Request $request)
    {
        // $hotelId = $request->header('hotel_id');

        // $blackoutDates = BlackoutDate::where('hotel_id', $hotelId)->with('categories')->paginate(10);
        $blackoutDates = BlackoutDate::with('categories')->paginate(10);

        if ($blackoutDates->isEmpty()) {
            return ['status' => 'blackoutDate_not_found'];
        }
        return [
            'status' => 'success',
            'blackoutDates' => BlackoutDateResource::collection($blackoutDates),
        ];
    }

    public function showBlackoutDateRepository(int $id, Request $request)
    {
        // $hotelId = $request->input('hotel_id');

        // $blackoutDate = BlackoutDate::where('hotel_id', $hotelId)
        $blackoutDate = BlackoutDate::whereNull('deleted_at')
            ->with('categories')
            ->where('id', $id)
            ->first();

        if (!$blackoutDate) {
            return ['status' => 'blackoutDate_not_found'];
        }

        return [
            'status' => 'success',
            'blackoutDate' => new BlackoutDateResource($blackoutDate),
        ];
    }
    public function storeBlackoutDateRepository(BlackoutDateDTO $request)
    {
        try {
            DB::beginTransaction();
            // Create blackoutDate
            $record = BlackoutDate::create([
                'name' => [
                    'en' => $request->blackoutDate_name['en'] ?? null,
                    'ar' => $request->blackoutDate_name['ar'] ?? null,
                ],
                'start_date' => $request->blackoutDate_start_date,
                'end_date' => $request->blackoutDate_end_date,
                'allow_existing_booking' => $request->allow_existing_booking,
                'hotel_id' => $request->hotel_id,
            ]);

            // Assign blackout_id to selected categories
            if (!empty($request->category_ids)) {
                $record->categories()->attach($request->category_ids);
            }
            DB::commit();
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

    public function updateBlackoutDateRepository(int $id ,BlackoutDateDTO $request){
        try {
            DB::beginTransaction();
            // Update blackoutDate
            // $hotelId=$request->hotel_id;
            $record = BlackoutDate::where('id',$id)
                // ->where('hotel_id',$hotelId)
                ->whereNull('deleted_at')
                ->first();
            if (!$record) {
                return ['status' => 'blackoutDate_not_found'];
            }
            $updateData = [];
            if (isset($request->blackoutDate_name['en']) || isset($request->blackoutDate_name['ar'])) {
                $updateData['name'] = [
                    'en' => $request->blackoutDate_name['en'] ?? $record->getTranslation('name', 'en'),
                    'ar' => $request->blackoutDate_name['ar'] ?? $record->getTranslation('name', 'ar'),
                ];
            }
            if (isset($request->blackoutDate_start_date)) {
                $updateData['start_date']=$request->blackoutDate_start_date;
            }
            if (isset($request->blackoutDate_end_date)) {
                $updateData['end_date']=$request->blackoutDate_end_date;
            }
            if (isset($request->allow_existing_booking)) {
                $updateData['allow_existing_booking']=$request->allow_existing_booking;
            }

            if (empty($updateData)&& empty($request->category_ids)) {
                return ['status' => 'not_have_date'];
            }

            if (!empty($updateData)) {
                $record->update($updateData);
            }
            // Use sync instead of attach to prevent duplicates
            if (!empty($request->category_ids)) {
                $record->categories()->sync($request->category_ids);
            }
            DB::commit();
            return [
                'status' => 'success',
                'data' => [],
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    public function deleteBlackoutDateRepository(Request $request,int $id){
        try {
            // $hotelId = $request->input('hotel_id');

            // Find BlackoutDate by ID and hotel_id
            $record = BlackoutDate::where('id', $id)
                // ->where('hotel_id', $hotelId)
                ->whereNull('deleted_at')
                ->first();
            if (!$record) {
                return ['status' => 'blackoutDate_not_found'];
            }
            $record->delete(); // Use soft delete
            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error'];
        }
    }
}
