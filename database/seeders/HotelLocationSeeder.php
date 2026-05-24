<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelLocation;

class HotelLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HotelLocation::create([
            'property_code' => 'HTL001',
            'display_name' => 'Grand Luxor Hotel',
            'default_language' => 'en',
            'default_currency' => 'USD',
            'timezone' => 'Africa/Cairo',
            'is_active' => true,
            'lat' => 25.68724356,
            'long' => 32.63945123,
            'hotel_video_url' => 'https://example.com/hotel-video.mp4',
            'location_name' => 'Luxor, Egypt',
            'address' => [
                'en' => '123 Nile Street, Luxor, Egypt',
                'ar' => '١٢٣ شارع النيل، الأقصر، مصر'
            ],
        ]);

        HotelLocation::create([
            'property_code' => 'HTL002',
            'display_name' => 'Alexandria Seaside Resort',
            'default_language' => 'en',
            'default_currency' => 'EGP',
            'timezone' => 'Africa/Cairo',
            'is_active' => true,
            'lat' => 31.2000924,
            'long' => 29.9187387,
            'hotel_video_url' => 'https://example.com/seaside-video.mp4',
            'location_name' => 'Alexandria, Egypt',
            'address' => [
                'en' => '456 Mediterranean Avenue, Alexandria, Egypt',
                'ar' => '٤٥٦ شارع البحر المتوسط، الإسكندرية، مصر'
            ],
        ]);
    }
}
