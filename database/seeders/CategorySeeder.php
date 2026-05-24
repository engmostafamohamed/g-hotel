<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => [
                'en' => 'Standard',
                'ar' => 'قياسي',
            ],
            'description' => [
                'en' => 'Standard room category for basic stays.',
                'ar' => 'فئة الغرف القياسية للإقامات الأساسية.',
            ],
            'images' => ['standard-room.jpg'],
            'hotel_id' => 1,
            'max_adults' => 2,
            'max_children' => 2,
            'infants_allowed' => true,
            'policies' => [
                ['en' => 'No smoking', 'ar' => 'ممنوع التدخين'],
                ['en' => 'Pets allowed', 'ar' => 'مسموح بالحيوانات الأليفة'],
            ],
        ]);
    }
}
