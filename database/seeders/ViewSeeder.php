<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\View;

class ViewSeeder extends Seeder
{
    public function run(): void
    {
        $views = [
            ['en' => 'Ocean View', 'ar' => 'إطلالة على البحر'],
            ['en' => 'Pool View', 'ar' => 'إطلالة على المسبح'],
            ['en' => 'Garden View', 'ar' => 'إطلالة على الحديقة'],
            ['en' => 'City View', 'ar' => 'إطلالة على المدينة'],
        ];

        foreach ($views as $type) {
            View::create(['type' => $type]);
        }
    }
}
