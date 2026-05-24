<?php

namespace Database\Seeders;

use App\Models\Exception;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceTimeSlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ServiceReservationTestingSeeder extends Seeder
{
    public function run(): void
    {
        $category = ServiceCategory::firstOrCreate(['name' => 'Spa'], ['description' => 'Spa treatments']);

        // 1. Service with full weekly schedule and multiple time slots
        $schedulableService = Service::create([
            'name' => ['en' => 'Full Spa Service'],
            'description' => ['en' => 'Relaxing treatments'],
            'price' => 100,
            'category_id' => $category->id,
            'hotel_id' => 1,
            'locations' => ['lobby'],
        ]);

        foreach (range(0, 6) as $day) {
            Schedule::create([
                'schedulable_id' => $schedulableService->id,
                'schedulable_type' => Service::class,
                'day_of_week' => $day,
                'work_from' => '09:00:00',
                'work_to' => '18:00:00',
            ]);
        }

        ServiceTimeSlot::create([
            'service_id' => $schedulableService->id,
            'start' => '10:00',
            'end' => '11:00',
            'max_capacity' => 2,
        ]);
        ServiceTimeSlot::create([
            'service_id' => $schedulableService->id,
            'start' => '11:00',
            'end' => '12:00',
            'max_capacity' => 1,
        ]);

        Exception::create([
            'schedulable_id' => $schedulableService->id,
            'schedulable_type' => Service::class,
            'date' => now()->addDays(1)->toDateString(),
            'exception_from' => '10:00',
            'exception_to' => '12:00',
        ]);

        // 2. Service with no schedule (unschedulable)
        Service::create([
            'name' => ['en' => 'No Schedule Massage'],
            'description' => ['en' => 'No schedule yet'],
            'price' => 70,
            'category_id' => $category->id,
            'hotel_id' => 1,
            'locations' => ['room'],
        ]);

        // 3. Service with schedule but no time slots
        $noSlotsService = Service::create([
            'name' => ['en' => 'No Slot Facial'],
            'description' => ['en' => 'Only schedules exist'],
            'price' => 90,
            'category_id' => $category->id,
            'hotel_id' => 1,
            'locations' => ['spa'],
        ]);

        Schedule::create([
            'schedulable_id' => $noSlotsService->id,
            'schedulable_type' => Service::class,
            'day_of_week' => now()->dayOfWeek,
            'work_from' => '10:00:00',
            'work_to' => '14:00:00',
        ]);

        // 4. Service with overlapping exception (unavailable due to full exception)
        $fullyExceptionedService = Service::create([
            'name' => ['en' => 'Unavailable Service'],
            'description' => ['en' => 'Always unavailable'],
            'price' => 120,
            'category_id' => $category->id,
            'hotel_id' => 1,
            'locations' => ['garden'],
        ]);

        Schedule::create([
            'schedulable_id' => $fullyExceptionedService->id,
            'schedulable_type' => Service::class,
            'day_of_week' => now()->dayOfWeek,
            'work_from' => '09:00:00',
            'work_to' => '17:00:00',
        ]);

        ServiceTimeSlot::create([
            'service_id' => $fullyExceptionedService->id,
            'start' => '12:00',
            'end' => '13:00',
            'max_capacity' => 1,
        ]);

        Exception::create([
            'schedulable_id' => $fullyExceptionedService->id,
            'schedulable_type' => Service::class,
            'date' => now()->toDateString(),
            'exception_from' => '09:00',
            'exception_to' => '17:00',
        ]);
    }
}
