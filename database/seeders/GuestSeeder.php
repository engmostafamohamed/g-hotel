<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Guest::create([
            'guest_title' => 'Mr.',
            'first_name' => 'John',
            'last_name' => 'Doe',
            // 'passport_no' => 'A1234567',
            'passport_or_id_flag' => 'passport',
            'passport_or_id_num' => 'A1234567',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
            'phone_no' => '+1234567890',
            'is_loyalty_member' => true,
            'member_since' => now()->subYears(2),
            // 'loyalty_tier' => 1,
            'total_points' => 5200,
            'is_verified' => true,
            'status' => 'active',
            'country_id' => 1,
            'city_id' => 1,
        ]);

        Guest::create([
            'guest_title' => 'Ms.',
            'first_name' => 'Sarah',
            'last_name' => 'Lee',
            // 'passport_no' => 'B7654321',
            'passport_or_id_flag' => 'id',
            'passport_or_id_num' => 'ID87654321',
            'email' => 'sarah.lee@example.com',
            'password' => Hash::make('securepass'),
            'phone_no' => '+1987654321',
            'is_loyalty_member' => false,
            'member_since' => null,
            // 'loyalty_tier' => 1,
            'total_points' => 0,
            'is_verified' => false,
            'status' => 'active',
            'country_id' => 1,
            'city_id' => 2,
        ]);

        Guest::create([
            'guest_title' => 'Dr.',
            'first_name' => 'Ali',
            'last_name' => 'Hassan',
            // 'passport_no' => 'C9876543',
            'passport_or_id_flag' => 'passport',
            'passport_or_id_num' => 'C9876543',
            'email' => 'ali.hassan@example.com',
            'password' => Hash::make('passw0rd'),
            'phone_no' => '+201234567890',
            'is_loyalty_member' => true,
            'member_since' => now()->subMonths(8),
            // 'loyalty_tier' => '1',
            'total_points' => 2400,
            'is_verified' => true,
            'status' => 'active',
            'country_id' => 2,
            'city_id' => 3,
        ]);

    }
}
