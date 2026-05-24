<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CountryCitySeeder::class,
            HotelLocationSeeder::class,
            RestaurantMenuSeeder::class,
            EmployeeSeeder::class,
            GuestSeeder::class,
            StaticPageSeeder::class,
            BedSeeder::class,
            ViewSeeder::class,
            ContactInfoSeeder::class
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
