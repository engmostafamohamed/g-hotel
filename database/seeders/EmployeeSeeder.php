<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                // 'hotel_code' => 'HTL001',
                'hotel_id' => '1',
                'primary_role' => 'admin',
                'name' => 'Neveen Fahmy',
                'cnic' => '1234567890123',
                'phone_no' => '01001234567',
                'email' => 'neveen@example.com',
                'password' => Hash::make('password123'),
                'salary' => 12000,
            ],
            [
                // 'hotel_code' => 'HTL002',
                'hotel_id' => '2',
                'primary_role' => 'marketing',
                'name' => 'Sarah Ibrahim',
                'cnic' => '9876543210987',
                'phone_no' => '01007654321',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password123'),
                'salary' => 10000,
            ],
        ];

        foreach ($employees as $data) {
            $employee = Employee::create($data);
            $employee->assignRole($data['primary_role']); // uses Spatie's method
        }
    }
}
