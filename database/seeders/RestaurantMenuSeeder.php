<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelLocation;
use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class RestaurantMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Create a restaurant
        $restaurant = Restaurant::create([
            'name' => ['en' => 'Mediterranean Breeze', 'ar' => 'نسيم البحر المتوسط'],
            'cuisine' => ['en' => 'Mediterranean', 'ar' => 'متوسطي'],
            'image_url' => 'https://example.com/restaurant.jpg',
            'hotel_id' => '1',
        ]);

        // Define some menu categories and items
        $categories = [
            [
                'name' => 'Starters',
                'items' => [
                    [
                        'name' => 'Hummus Platter',
                        'description' => 'With warm pita and olive oil',
                        'price' => 12.50,
                        'dietary_tags' => ['vegetarian', 'gluten-free'],
                    ],
                    [
                        'name' => 'Falafel Bites',
                        'description' => 'Served with tahini sauce',
                        'price' => 10.00,
                        'dietary_tags' => ['vegan'],
                    ],
                ],
            ],
            [
                'name' => 'Main Course',
                'items' => [
                    [
                        'name' => 'Grilled Sea Bass',
                        'description' => 'Served with seasonal vegetables',
                        'price' => 35.00,
                        'dietary_tags' => ['gluten-free'],
                    ],
                    [
                        'name' => 'Chicken Shawarma Plate',
                        'description' => 'With rice and salad',
                        'price' => 22.00,
                        'dietary_tags' => [],
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = MenuCategory::create([
                'restaurant_id' => $restaurant->id,
                'name' => $categoryData['name'],
            ]);

            foreach ($categoryData['items'] as $itemData) {
                MenuItem::create([
                    'menu_category_id' => $category->id,
                    'name' => $itemData['name'],
                    'description' => $itemData['description'],
                    'price' => $itemData['price'],
                    'dietary_tags' => $itemData['dietary_tags'],
                ]);
            }
        }
    }
}
