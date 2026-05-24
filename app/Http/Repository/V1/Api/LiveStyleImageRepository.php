<?php

namespace App\Http\Repository\V1\Api;
use App\Models\LiveStyleImage;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
class LiveStyleImageRepository
{
    public function showLiveStyleImageRepository(Request $request)
    {
        app()->setLocale($request->header('Accept-Language', 'en'));

        $locale = app()->getLocale();

        $hotelId = $request->header('hotel-id') ?? 1;
        if (!$hotelId) {
            return ['status' => 'not_found'];
        }

        $query = LiveStyleImage::where('hotel_id', $hotelId)->paginate(10);

        if ($query->isEmpty()) {
            return ['status' => 'not_found'];
        }

        $images = $query->through(function ($item) use ($locale) {
            return [
                'live_style_image_caption' => $item->getTranslation('caption', $locale),
                'live_style_image_url' => $item->image_url,
                'hotel_id' => $item->hotel_id,
            ];
        });

        return [
            'status' => 'success',
            'liveStyleImages' => $images,
        ];
    }

    public function storeLiveStyleImageRepository(Request $request)
    {
        try {
            if (!$request->hasFile('liveStyleImage_image')) {
                return ['status' => 'image_not_found'];
            }

            $imagePath = FileUpload::uploadImageOnLocal($request->file('liveStyleImage_image'), 'LiveStyleImage');

            $record = LiveStyleImage::create([
                'caption' => [
                    'en' => $request->input('liveStyleImage_caption.en'),
                    'ar' => $request->input('liveStyleImage_caption.ar'),
                ],
                'image_url' => $imagePath,
                'hotel_id' => $request->input('hotel_id'),
            ]);
            return [
                'status' => 'success',
                'data' => $record,
            ];

        } catch (QueryException $e) {
            return ['status' => 'db_error'];

        } catch (Exception $e) {
            return ['status' => 'error'];
        }
    }
}
