<?php

namespace App\Services\V1\CRM\ServiceReservation;

use App\DataTransferObjects\V1\CRM\ServiceReservation\StoreServiceReservationDTO;
use App\DataTransferObjects\V1\CRM\ServiceReservation\UpdateServiceReservationDTO;
use App\Http\Repository\V1\CRM\ServiceReservation\ServiceReservationRepository;
use App\Models\Exception;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceReservation;
use App\Models\ServiceTimeSlot;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ServiceReservationService
{
    public function __construct(
        protected ServiceReservationRepository $repository
    ) {}

    public function makeReservation(StoreServiceReservationDTO $dto): ServiceReservation
    {
        return ($dto->date && $dto->from && $dto->to)
            ? $this->createSchedulableReservation($dto)
            : $this->createNonSchedulableReservation($dto);
    }

    private function createSchedulableReservation(StoreServiceReservationDTO $dto): ServiceReservation
    {
        // conditional date and time validation handled in request withValidator function
        // if (!$dto->date || !$dto->from || !$dto->to) {
        //     throw ValidationException::withMessages([
        //         'datetime' => [__('service_reservations.datetime_required')]
        //     ]);
        // }
        $serviceId = $dto->service_id;
        $date = Carbon::parse($dto->date);
        $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday) (parsed to string day name in isScheduled function)

        if (!$this->repository->isScheduled($serviceId, $dayOfWeek, $dto->from, $dto->to)) {
            throw ValidationException::withMessages([
                'schedule' => [__('service_reservations.schedule_unavailable')]
            ]);
        }

        if ($this->repository->hasException($serviceId, $dto->date, $dto->from, $dto->to)) {
            throw ValidationException::withMessages([
                'exception' => [__('service_reservations.exception_unavailable')]
            ]);
        }

        $timeSlot = $this->repository->getMatchingTimeSlot($serviceId, $dto->from, $dto->to);

        if (!$timeSlot) {
            throw ValidationException::withMessages([
                'time_slot' => [__('service_reservations.invalid_time_slot')]
            ]);
        }

        if (!$this->repository->isTimeSlotAvailable($dto->date, $timeSlot->id)) {
            throw ValidationException::withMessages([
                'time_slot' => [__('service_reservations.fully_booked')]
            ]);
        }

        return $this->repository->create([
            'guest_id' => $dto->guest_id,
            'service_id' => $serviceId,
            'service_time_slot_id' => $timeSlot->id,
            'date' => $dto->date,
            'notes' => $dto->notes,
            'status' => 'confirmed',
            'confirmed_by' => Auth::guard('employee')->id()
        ]);
    }

    private function createNonSchedulableReservation(StoreServiceReservationDTO $dto): ServiceReservation
    {
        return $this->repository->create([
            'guest_id' => $dto->guest_id,
            'service_id' => $dto->service_id,
            'notes' => $dto->notes,
            'status' => 'confirmed',
            'confirmed_by' => Auth::guard('employee')->id()
        ]);
    }

    public function update(int $id, UpdateServiceReservationDTO $dto): ServiceReservation
    {
        $existing = $this->repository->findById($id);

        $isSchedulable = $existing->service->isSchedulable();

        $shouldRevalidateSchedule = $dto->date || $dto->from || $dto->to;

        if ($isSchedulable && $shouldRevalidateSchedule) {
            return $this->updateSchedulableReservation($existing, $dto);
        }

        return $this->repository->update($existing, $dto);
    }

    private function updateSchedulableReservation(ServiceReservation $existing, UpdateServiceReservationDTO $dto): ServiceReservation
    {
        $date = $dto->date ?? $existing->date;
        $from = $dto->from ?? optional($existing->timeSlot)->start;
        $to = $dto->to ?? optional($existing->timeSlot)->end;

        if (!$date || !$from || !$to) {
            throw ValidationException::withMessages([
                'datetime' => [__('service_reservations.datetime_required')]
            ]);
        }

        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $hasSchedule = $this->repository->isScheduled($existing->service_id, $dayOfWeek, $from, $to);

        if (!$hasSchedule) {
            throw ValidationException::withMessages([
                'schedule' => [__('service_reservations.schedule_unavailable')]
            ]);
        }

        $hasException = $this->repository->hasException($existing->service_id, $date, $from, $to);

        if ($hasException) {
            throw ValidationException::withMessages([
                'exception' => [__('service_reservations.exception_unavailable')]
            ]);
        }

        $timeSlot = $this->repository->getMatchingTimeSlot($existing->service_id, $from, $to);

        if (!$timeSlot) {
            throw ValidationException::withMessages([
                'time_slot' => [__('service_reservations.invalid_time_slot')]
            ]);
        }

        $originalTimeSlotId = optional($existing->timeSlot)->id;

        //parse dates to match format
        $dtoDate = Carbon::parse($dto->date)->toDateString();
        $existingDate = Carbon::parse($existing->date)->toDateString();

        $isSameSlot = $originalTimeSlotId === $timeSlot->id && $existingDate === $dtoDate;

        if (!$isSameSlot && !$this->repository->isTimeSlotAvailable($date, $timeSlot->id)) {
            throw ValidationException::withMessages([
                'time_slot' => [__('service_reservations.fully_booked')]
            ]);
        }

        if ($dto->cancellation_reason && $dto->status !== 'cancelled') {
            throw ValidationException::withMessages([
                'cancellation_reason' => [__('service_reservations.cancellation_mismatch')]
            ]);
        }

        return $this->repository->update(
            $existing,
            $dto,
            $timeSlot->id
        );
    }


    public function list(Request $request)
    {
        //add filter by service_category? this way getGuestsByServiceCategory in the repository will be basically useless.
        //other than the fact that the response is grouped by guest (which is done in the resource anyways), they are the same endpoint minus the category from the route param
        $filters = $request->only(['hotel_id', 'service_id', 'service_category_id', 'status', 'date_from', 'date_to', 'time_from', 'time_to', 'guest_id']);

        return $this->repository->list($filters);
    }

    public function find(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getGuestsByServiceCategory(int $categoryId, Request $request): LengthAwarePaginator
    {
        $filters = $request->only(['hotel_id', 'service_id', 'status', 'date_from', 'date_to', 'time_from', 'time_to', 'guest_id']);

        //add category id to filters
        $filters['service_category_id'] = $categoryId;

        return $this->repository->list($filters);
    }
  
}