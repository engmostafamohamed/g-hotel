<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bed;

class BedSeeder extends Seeder
{
    public function run(): void
    {
        $beds = [
            ['en' => 'King Bed', 'ar' => 'سرير ملكي'],
            ['en' => 'Queen Bed', 'ar' => 'سرير ملكة'],
            ['en' => 'Twin Bed', 'ar' => 'سريران مفردان'],
            ['en' => 'Double Bed', 'ar' => 'سرير مزدوج'],
            ['en' => 'Single Bed', 'ar' => 'سرير مفرد'],
            ['en' => 'Sofa Bed', 'ar' => 'سرير أريكة'],
            ['en' => 'Bunk Bed', 'ar' => 'سرير بطابقين'],
            ['en' => 'Murphy Bed', 'ar' => 'سرير قابل للطي'],
            ['en' => 'Rollaway Bed', 'ar' => 'سرير إضافي متحرك'],
            ['en' => 'Day Bed', 'ar' => 'سرير نهاري'],
            ['en' => 'Futon', 'ar' => 'فوتون'],
            ['en' => 'Crib', 'ar' => 'سرير أطفال'],
        ];

        foreach ($beds as $type) {
            Bed::create(['type' => $type]);
        }
    }
}
