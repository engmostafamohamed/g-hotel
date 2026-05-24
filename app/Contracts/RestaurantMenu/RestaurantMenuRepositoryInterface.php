<?php

namespace App\Contracts\RestaurantMenu;

use App\Models\Restaurant;

interface RestaurantMenuRepositoryInterface
{
    public function findRestaurantByLocation($location);
    public function storeMenuItem($categoryId, array $data, $updateExisting = false);
    public function findOrCreateCategory(Restaurant $restaurant, $categoryName);

}
