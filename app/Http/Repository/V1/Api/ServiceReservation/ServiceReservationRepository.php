<?php

namespace App\Http\Repository\V1\Api\ServiceReservation;

use App\DataTransferObjects\V1\Api\ServiceReservation\UpdateServiceReservationDTO;
use App\Models\Exception;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceReservation;
use App\Models\ServiceTimeSlot;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ServiceReservationRepository
{
    public function create(array $data): ServiceReservation
    {
        return ServiceReservation::create($data);
    }

    public function update(ServiceReservation $reservation, UpdateServiceReservationDTO $dto, ?int $timeSlotId = null): ServiceReservation
    {
        $data = [];

        if ($dto->date) $data['date'] = $dto->date;
        if ($dto->notes) $data['notes'] = $dto->notes;
        if ($dto->status) $data['status'] = $dto->status;
        if ($dto->cancellation_reason) $data['cancellation_reason'] = $dto->cancellation_reason;
        if ($timeSlotId) $data['service_time_slot_id'] = $timeSlotId;

        $reservation->update($data);
        $reservation->refresh()->load(['timeSlot', 'service']);

        return $reservation;
    }

    public function listForGuest(int $guestId, array $filters): LengthAwarePaginator
    {
        $query = ServiceReservation::with(['timeSlot', 'service'])
            ->where('guest_id', $guestId);

        if ($obligatoryHotelId = current_hotel_id()) {
            $query->whereHas('service', function ($q) use ($obligatoryHotelId) {
                $q->where('hotel_id', $obligatoryHotelId);
            });
        }

        $query->when($filters['service_id'] ?? null, fn($q, $v) => $q->where('service_id', $v))
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['date'] ?? null, function ($query, $value) {
                $query->where(function ($q) use ($value) {
                    $q->where(function ($q1) use ($value) {
                        $q1->whereHas('service', fn($q2) => $q2->whereHas('timeSlots')) // schedulable
                            ->whereDate('date', '=', $value);
                    });
                    $q->orWhere(function ($q1) use ($value) {
                        $q1->whereHas('service', fn($q2) => $q2->whereDoesntHave('timeSlots')) // not schedulable
                            ->whereDate('created_at', '=', $value);
                    });
                });
            })
            ->when($filters['from'] ?? null, function ($query, $value) {
                $query->where(function ($q) use ($value) {
                    $q->whereHas('service', fn($q2) => $q2->whereHas('timeSlots')) // schedulable
                        ->whereHas('timeSlot', fn($q3) => $q3->where('start', '>=', $value))
                        ->orWhere(function ($q2) use ($value) {
                            $q2->whereHas('service', fn($q3) => $q3->whereDoesntHave('timeSlots')) // not schedulable
                                ->whereTime('created_at', '>=', $value);
                        });
                });
            })
            ->when($filters['to'] ?? null, function ($query, $value) {
                $query->where(function ($q) use ($value) {
                    $q->whereHas('service', fn($q2) => $q2->whereHas('timeSlots')) // schedulable
                        ->whereHas('timeSlot', fn($q3) => $q3->where('end', '<=', $value))
                        ->orWhere(function ($q2) use ($value) {
                            $q2->whereHas('service', fn($q3) => $q3->whereDoesntHave('timeSlots')) // not schedulable
                                ->whereTime('created_at', '<=', $value);
                        });
                });
            });

        $perPage = request()->query('per_page', 10);

        return $query->latest('created_at')->paginate((int) $perPage);
    }

    public function findForGuest(int $id, int $guestId)
    {
        return ServiceReservation::with(['timeSlot', 'service'])
            ->where('id', $id)
            ->where('guest_id', $guestId)
            ->when(current_hotel_id(), function ($q, $hotelId) {
                $q->whereHas('service', function ($sub) use ($hotelId) {
                    $sub->where('hotel_id', $hotelId);
                });
            })
            ->firstOrFail();
    }

    public function findById(int $id): ServiceReservation
    {
        return ServiceReservation::with(['timeSlot', 'service'])->findOrFail($id);
    }

    public function isTimeSlotAvailable(string $date, int $timeSlotId): bool
    {
        $timeSlot = ServiceTimeSlot::findOrFail($timeSlotId);
        return $timeSlot->remainingCapacity($date) > 0;
    }

    public function isScheduled(int $serviceId, int $dayOfWeek, string $from, string $to): bool
    {
        // Map numeric day (0–6) → string day (sunday–saturday)
        $dayNames = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];

        $dayString = $dayNames[$dayOfWeek] ?? null;

        return Schedule::where('schedulable_type', 'service')
            ->where('schedulable_id', $serviceId)
            ->where('day_of_week', $dayString)
            ->whereTime('work_from', '<=', $from)
            ->whereTime('work_to', '>=', $to)
            ->exists();
    }


    public function hasException(int $serviceId, string $date, string $from, string $to): bool
    {
        return Exception::where('schedulable_type', 'service')
            ->where('schedulable_id', $serviceId)
            ->whereDate('date', $date)
            ->whereTime('exception_from', '<=', $from)
            ->whereTime('exception_to', '>=', $to)
            ->exists();
    }

    public function getMatchingTimeSlot(int $serviceId, string $from, string $to)
    {
        return ServiceTimeSlot::where('service_id', $serviceId)
            ->whereTime('start', $from)
            ->whereTime('end', $to)
            ->first();
    }
}