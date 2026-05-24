<?php
namespace Database\Seeders;

use App\Models\Country;
use App\Models\City;
use Illuminate\Database\Seeder;

class CountryCitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => ['en' => 'Saudi Arabia', 'ar' => 'المملكة العربية السعودية'],
                'iso_code' => 'SA',
                'country_code' => '+966',
                'cities' => [
                    ['en' => 'Riyadh', 'ar' => 'الرياض'],
                    ['en' => 'Jeddah', 'ar' => 'جدة'],
                    ['en' => 'Dammam', 'ar' => 'الدمام'],
                    ['en' => 'Mecca', 'ar' => 'مكة'],
                    ['en' => 'Medina', 'ar' => 'المدينة المنورة'],
                    ['en' => 'Khobar', 'ar' => 'الخبر'],
                    ['en' => 'Abha', 'ar' => 'أبها'],
                ],
            ],
            [
                'name' => ['en' => 'United Arab Emirates', 'ar' => 'الإمارات العربية المتحدة'],
                'iso_code' => 'AE',
                'country_code' => '+971',
                'cities' => [
                    ['en' => 'Dubai', 'ar' => 'دبي'],
                    ['en' => 'Abu Dhabi', 'ar' => 'أبو ظبي'],
                    ['en' => 'Sharjah', 'ar' => 'الشارقة'],
                    ['en' => 'Ajman', 'ar' => 'عجمان'],
                    ['en' => 'Fujairah', 'ar' => 'الفجيرة'],
                ],
            ],[
                'name' => ['en' => 'Jordan', 'ar' => 'الأردن'],
                'iso_code' => 'JO',
                'country_code' => '+962',
                'cities' => [
                    ['en' => 'Amman', 'ar' => 'عمان'],
                    ['en' => 'Irbid', 'ar' => 'إربد'],
                    ['en' => 'Zarqa', 'ar' => 'الزرقاء'],
                    ['en' => 'Aqaba', 'ar' => 'العقبة'],
                ],
            ],
            [
                'name' => ['en' => 'Egypt', 'ar' => 'مصر'],
                'iso_code' => 'EG',
                'country_code' => '+20',
                'cities' => [
                    ['en' => 'Cairo', 'ar' => 'القاهرة'],
                    ['en' => 'Alexandria', 'ar' => 'الإسكندرية'],
                    ['en' => 'Giza', 'ar' => 'الجيزة'],
                    ['en' => 'Mansoura', 'ar' => 'المنصورة'],
                    ['en' => 'Tanta', 'ar' => 'طنطا'],
                    ['en' => 'Aswan', 'ar' => 'أسوان'],
                ],
            ],
        ];

        foreach ($data as $countryData) {
            $country = Country::create([
                'name' => $countryData['name'],
                'iso_code' => $countryData['iso_code'],
                'country_code' => $countryData['country_code'],
            ]);

            foreach ($countryData['cities'] as $cityName) {
                City::create([
                    'name' => $cityName,
                    'country_id' => $country->id,
                ]);
            }
        }
    }
}
