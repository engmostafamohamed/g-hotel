<?php

namespace App\Http\Repository\V1\CRM\RestaurantMenu;

use App\Contracts\RestaurantMenu\RestaurantMenuRepositoryInterface;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Traits\UsesHotelScope;

class RestaurantMenuRepository implements RestaurantMenuRepositoryInterface
{
    use UsesHotelScope;
    public function findRestaurantByLocation($location)
    {
        $query = Restaurant::whereHas('hotelLocation', function ($q) use ($location) {
            $q->where('property_code', $location);
        });
        if ($hotelId = $this->getHotelIdFromAuth()) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->first();
    }

    public function storeMenuItem($categoryId, array $data, $updateExisting = false)
    {
        if ($updateExisting && !empty($data['code'])) {
            return MenuItem::updateOrCreate(
                ['code' => $data['code'], 'menu_category_id' => $categoryId],
                $data
            );
        }

        return MenuItem::create(array_merge(['menu_category_id' => $categoryId], $data));
    }

    public function findOrCreateCategory(Restaurant $restaurant, $categoryName)
    {
        return MenuCategory::firstOrCreate([
            'restaurant_id' => $restaurant->id,
            'name' => $categoryName,
        ]);
    }
}
