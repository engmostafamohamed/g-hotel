<?php

namespace Database\Seeders;

use App\Models\Tier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tier::create([
            'tier_name'  => [
                'ar' => 'الذهبية',
                'en' => 'Gold',
            ],
            'min_nights' => 3,
            'tier_value' => 10.5,
        ]);
    }
}
