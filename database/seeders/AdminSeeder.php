<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!Employee::where('email', 'admin@ghotel.com')->exists()) {
            $admin = Employee::firstOrCreate(
                ['email' => 'admin@ghotel.com'],
                [
                    'primary_role' => 'admin',
                    'name' => 'Admin User',
                    'cnic' => '12345-6789012-3',
                    'phone_no' => '01000000000',
                    'password' => Hash::make('admin123'),
                    'salary' => 10000.00
                ]
            );

            $admin->assignRole('admin');
        }
    }
}
