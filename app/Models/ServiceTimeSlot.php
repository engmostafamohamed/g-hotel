<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ServiceTimeSlot extends Model
{
    protected $table = 'service_time_slots';
    protected $fillable = [
        'service_id',
        'start',
        'end',
        'max_capacity'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceReservations()
    {
        return $this->hasMany(ServiceReservation::class, 'service_time_slot_id');
    }

    //takes input date format 2025-08-01 and checks remaining capacity of the slot during that date
    //usage: $timeSlot = ServiceTimeSlot::find(3); $remaining = $timeSlot->remainingCapacity('2025-08-01');
    public function remainingCapacity(Carbon|string $date): int
    {
        $date = Carbon::parse($date)->toDateString(); // Normalize the date

        $reservedCount = $this->serviceReservations()
            ->whereDate('date', $date)
            ->whereIn('status', ['confirmed', 'completed']) // Only count valid statuses
            ->count();

        return max(0, $this->max_capacity - $reservedCount);
    }
}
