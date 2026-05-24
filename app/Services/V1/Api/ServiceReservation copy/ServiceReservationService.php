<?php

namespace App\Services\V1\Api\ServiceReservation;

use App\DataTransferObjects\V1\Api\ServiceReservation\StoreServiceReservationDTO;
use App\DataTransferObjects\V1\Api\ServiceReservation\UpdateServiceReservationDTO;
use App\Http\Repository\V1\Api\ServiceReservation\ServiceReservationRepository;
use App\Models\Exception;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceReservation;
use App\Models\ServiceTimeSlot;
use Illuminate\Auth\Access\AuthorizationException;
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
        //wrap in a DB transaction if needed later when all logic is added (loyalty points, payments, etc)
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
        $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        if (!$this->repository->isScheduled($serviceId, $dayOfWeek, $dto->from, $dto->to)) {
            throw ValidationException::withMessages([
                'schedule' => [__('serviceReservation.schedule_unavailable')],
            ]);
        }

        if ($this->repository->hasException($serviceId, $dto->date, $dto->from, $dto->to)) {
            throw ValidationException::withMessages([
                'exception' => [__('serviceReservation.exception_unavailable')],
            ]);
        }

        $timeSlot = $this->repository->getMatchingTimeSlot($serviceId, $dto->from, $dto->to);

        if (!$timeSlot) {
            throw ValidationException::withMessages([
                'time_slot' => [__('serviceReservation.invalid_time_slot')],
            ]);
        }

        if (!$this->repository->isTimeSlotAvailable($dto->date, $timeSlot->id)) {
            throw ValidationException::withMessages([
                'time_slot' => [__('serviceReservation.fully_booked')],
            ]);
        }

        return $this->repository->create([
            'guest_id' => Auth::guard('guest')->id(),
            'service_id' => $serviceId,
            'service_time_slot_id' => $timeSlot->id,
            'date' => $dto->date,
            'notes' => $dto->notes,
            'status' => 'confirmed',
        ]);
    }

    private function createNonSchedulableReservation(StoreServiceReservationDTO $dto): ServiceReservation
    {
        return $this->repository->create([
            'guest_id' => Auth::guard('guest')->id(),
            'service_id' => $dto->service_id,
            'notes' => $dto->notes,
            'status' => 'confirmed',
        ]);
    }

    public function update(int $id, UpdateServiceReservationDTO $dto): ServiceReservation
    {
        $existing = $this->repository->findById($id);

        if ($existing->guest_id !== Auth::guard('guest')->id()) {
            throw new AuthorizationException(__('serviceReservation.not_allowed_modify'));
        }

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
                'schedule' => [__('serviceReservation.schedule_unavailable')],
            ]);
        }

        $hasException = $this->repository->hasException($existing->service_id, $date, $from, $to);

        if ($hasException) {
            throw ValidationException::withMessages([
                'exception' => [__('serviceReservation.exception_unavailable')],
            ]);
        }

        $timeSlot = $this->repository->getMatchingTimeSlot($existing->service_id, $from, $to);

        if (!$timeSlot) {
            throw ValidationException::withMessages([
                'time_slot' => [__('serviceReservation.invalid_time_slot')],
            ]);
        }

        $originalTimeSlotId = optional($existing->timeSlot)->id;

        //parse dates to match format
        $dtoDate = Carbon::parse($dto->date)->toDateString();
        $existingDate = Carbon::parse($existing->date)->toDateString();

        $isSameSlot = $originalTimeSlotId === $timeSlot->id && $existingDate === $dtoDate;

        if (!$isSameSlot && !$this->repository->isTimeSlotAvailable($date, $timeSlot->id)) {
            throw ValidationException::withMessages([
                'time_slot' => [__('serviceReservation.fully_booked')],
            ]);
        }

        if ($dto->cancellation_reason && $dto->status !== 'cancelled') {
            throw ValidationException::withMessages([
                'cancellation_reason' => [__('serviceReservation.cancellation_mismatch')],
            ]);
        }

        return $this->repository->update(
            $existing,
            $dto,
            $timeSlot->id
        );
    }

    public function listForGuest(Request $request)
    {
        $guestId = auth('guest')->id();
        $filters = $request->only(['service_id', 'status', 'date', 'from', 'to']);
        $filters['hotel_id'] = $request->header('hotel-id');
        return $this->repository->listForGuest($guestId, $filters);
    }

    public function findForGuest(int $id)
    {
        $guestId = auth('guest')->id();
        return $this->repository->findForGuest($id, $guestId);
    }
}
