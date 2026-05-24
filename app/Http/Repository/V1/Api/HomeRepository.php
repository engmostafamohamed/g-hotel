<?php

namespace App\Http\Repository\V1\Api;

use App\Models\User;
use App\Models\Service;
use App\Models\Restaurant;
use App\Models\LiveStyleImage;
use App\Models\HotelLocation;
use Carbon\Carbon;
use App\Models\Schedule;

class HomeRepository
{
    public function showHomeRepository($request)
    {
        $hotelId = $request->header('hotel-id') ?? 1;

        if (!$hotelId) {
            return ['status' => 'not_found'];
        }

        $hotel = HotelLocation::find($hotelId);



        

        $services = Service::where('hotel_id', operator: $hotelId)->get()->map(function ($service) {
            return [
                'name' => $service->getTranslation('name', app()->getLocale()),
                'image_url' => $service->image_url,
                'description' => $service->getTranslation('description', app()->getLocale())
            ];
        });

        $restaurants = Restaurant::where('hotel_id', $hotelId)
            ->get()
            ->map(function ($restaurant) {
                return [
                    'name' => $restaurant->getTranslation('name', app()->getLocale()),
                    'cuisine' => $restaurant->getTranslation('cuisine', app()->getLocale()),
                    'image_url' => $restaurant->image_url,
                    'schedules' => $restaurant->schedules->map(function ($schedule) {
                        return [
                            'day_of_week' => $schedule->day_of_week,
                            'work_from' => $schedule->work_from,
                            'work_to' => $schedule->work_to,
                        ];
                    }),
                ];
            });
        $lifestyleImages = LiveStyleImage::where('hotel_id', $hotelId)->get()->map(function ($img) {
            return [
                'images_url' => $img->images_url,
                'caption' => $img->getTranslation('caption', app()->getLocale())
            ];
        });

		//$hotelVideo = ($hotel && !empty($hotel->hotel_video_url)) ? $hotel->hotel_video_url : [];
		$hotelVideo = collect($lifestyleImages)->take(2);
        return [
            'status' => 'success',
            'homeData' => [
                'hotel_video' => $hotelVideo ?: [],
                'services' => $services,
                'restaurants' => $restaurants,
                'lifestyle_images' => $lifestyleImages
            ]
        ];
    }
}

