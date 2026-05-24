<?php

namespace App\DataTransferObjects\V1\CRM\ServiceReservation;

use App\Http\Requests\V1\CRM\ServiceReservation\UpdateServiceReservationRequest;
use Illuminate\Support\Facades\Log;

class UpdateServiceReservationDTO
{
    public function __construct(
        public ?string $from = null,
        public ?string $to = null,
        public ?string $date = null,
        public ?string $notes = null,
        public ?string $status = null,
        public ?string $cancellation_reason = null,
    ) {}

    public static function fromRequest(UpdateServiceReservationRequest $request): self
    {
        return new self(
            from: $request->input('from'),
            to: $request->input('to'),
            date: $request->input('date'),
            notes: $request->input('notes'),
            status: $request->input('status'),
            cancellation_reason: $request->input('cancellation_reason'),
        );
    }
}
