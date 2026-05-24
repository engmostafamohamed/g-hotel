<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceReservation extends Model
{
    use SoftDeletes;
    protected $table = 'service_reservations';

    protected $fillable = [
        'guest_id',
        'service_id',
        'service_time_slot_id',
        'date',
        'status',
        'notes',
        'confirmed_by',
        'cancelled_by',
        'cancellation_reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function timeSlot()
    {
        return $this->belongsTo(ServiceTimeSlot::class, 'service_time_slot_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Employee::class, 'confirmed_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(Employee::class, 'cancelled_by');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'service_reservation_id');
    }
}
