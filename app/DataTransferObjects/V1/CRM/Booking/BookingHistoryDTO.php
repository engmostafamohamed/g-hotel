<?php

namespace App\DataTransferObjects\V1\CRM\Booking;

class BookingHistoryDTO
{
    public function __construct(
        public int $hotel_id,
        public ?int $guest_id,
        public ?string $from_date,
        public ?string $to_date,
        public string $sort,
        public int $per_page
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            hotel_id:  (int) $request->input('hotel_id'),
            guest_id:  $request->input('guest_id') ? (int) $request->input('guest_id') : null,
            from_date: $request->input('from_date') ?: null,
            to_date:   $request->input('to_date') ?: null,
            sort:      $request->input('sort', 'desc'),
            per_page:  (int) $request->input('per_page', 10),
        );
    }
}
