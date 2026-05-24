<?php

namespace App\Http\Repository\V1\CRM\LiveStyleImage;

use App\Models\LiveStyleImage;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use App\Http\Resources\V1\CRM\LiveStyle\LiveStyleImageResource;

class LiveStyleImageRepository implements LiveStyleImageRepositoryInterface
{
    public function showAllLiveStyleImagesRepository(Request $request)
    {
        // $perPage = $request->get('per_page', 10);

        // Validate perPage to ensure it's a positive integer
        // if (!is_numeric($perPage) || $perPage < 0) {
        //     return ['status' => 'invalid_per_page'];
        // }
        // If perPage is not set or is less than 1, default to 10
        // if ($perPage < 1) {
        //     $perPage = 10;
        // }

        // If perPage is 0, return all records without pagination
        // $query = $perPage > 0
        //     ? LiveStyleImage::paginate($perPage)
        //     : LiveStyleImage::get();

        $query = LiveStyleImage::paginate(10);
        if ($query->isEmpty()) {
            return ['status' => 'not_found'];
        }

        return [
            'status' => 'success',
            'liveStyleImages' => $query,
        ];
    }

    public function showLiveStyleImageRepository(int $id, Request $request)
    {
        $query = LiveStyleImage::where('id', $id)->first();
        if (!$query) {
            return ['status' => 'not_found'];
        }


        return [
            'status' => 'success',
            'liveStyleImage' => $query,
        ];
    }

    public function  storeLiveStyleImageRepository(Request $request)
    {
        try {
            if (!$request->hasFile('images_url')) {
                return ['status' => 'image_not_found'];
            }
            $uploadedPaths = [];

            foreach ($request->file('images_url') as $file) {
                $uploadedPaths[] = FileUpload::uploadImageOnLocal(
                    $file,
                    'LiveStyleImages'
                );
            }

            LiveStyleImage::create([
                'caption' => [
                    'en' => $request->input('caption.en'),
                    'ar' => $request->input('caption.ar'),
                ],
                'images_url' => $uploadedPaths,
                'hotel_id'   => $request->input('hotel_id'),
            ]);

            return [
                'status' => 'success',
            ];
        } catch (QueryException $e) {
            return ['status' => 'db_error'];
        } catch (Exception $e) {
            return ['status' => 'error'];
        }
    }

    public function updateLiveStyleImageRepository(int $id, Request $request)
    {
        try {
            $hotelId = $request->input('hotel_id');
            $record = LiveStyleImage::where('id', $id)
                ->where('hotel_id', $hotelId)
                ->whereNull('deleted_at')
                ->first();
            if (!$record) {
                return ['status' => 'not_found'];
            }

            $updateData = [];
            if ($request->filled('caption.en') || $request->filled('caption.ar')) {
                $updateData['caption'] = [
                    'en' => $request->input('caption.en'),
                    'ar' => $request->input('caption.ar'),
                ];
            }

            if ($request->hasFile('images_url')) {
                $uploadedPaths = [];
                foreach ($request->file('images_url') as $file) {
                    // $imagesPath = FileUpload::uploadImageOnLocal($file, 'LiveStyleImages');
                    $imagesPath = FileUpload::uploadImageOnLocal($file, 'LiveStyleImages');
                    $uploadedPaths[] = $imagesPath;
                }
                // dd($record);
                // $existing = $record->images_url ?? [];
                // if (!is_array($existing)) {
                //     $existing = json_decode($existing, true) ?? [];
                // }

                // $updateData['images_url'] = json_encode(array_merge($existing, $uploadedPaths));
                $updateData['images_url'] = json_encode($uploadedPaths);
            }

            if ($request->has('hotel_id')) {
                $updateData['hotel_id'] = $request->input('hotel_id');
            }

            $record->update($updateData);

            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    public function deleteLiveStyleImageRepository(Request $request, int $id)
    {
        try {
            $hotelId = $request->header('hotel_id');

            // Find LiveStyleImage by ID and hotel_id
            $record = LiveStyleImage::where('id', $id)
                //->where('hotel_id', $hotelId)
                ->first();
            if (!$record) {
                return ['status' => 'image_not_found'];
            }
            $record->delete(); // Use soft delete

            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error'];
        }
    }
}
