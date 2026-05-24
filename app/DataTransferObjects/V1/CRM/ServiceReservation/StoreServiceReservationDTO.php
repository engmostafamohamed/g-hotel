<?php

namespace App\DataTransferObjects\V1\CRM\ServiceReservation;

use App\Http\Requests\V1\CRM\ServiceReservation\StoreServiceReservationRequest;

class StoreServiceReservationDTO
{
    public function __construct(
        public int $service_id,
        public int $guest_id,
        public string $from,
        public string $to,
        public string $date,
        public ?string $notes = null,
    ) {}

    public static function fromRequest(StoreServiceReservationRequest $request): self
    {
        return new self(
            service_id: $request->input('service_id'),
            guest_id: $request->input('guest_id'),
            from: $request->input('from'),
            to: $request->input('to'),
            date: $request->input('date'),
            notes: $request->input('notes')
        );
    }
}
