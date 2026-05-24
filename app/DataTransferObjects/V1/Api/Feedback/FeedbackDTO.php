<?php

namespace App\DataTransferObjects\V1\Api\Feedback;

use App\Http\Requests\V1\Api\Feedback\StoreFeedbackRequest;
use App\Http\Requests\V1\Api\Feedback\UpdateFeedbackRequest;

class FeedbackDTO
{
    public function __construct(
        public int $guest_id,
        public ?int $booking_id,
        public ?int $service_reservation_id,
        public ?int $rating,
        public ?string $comment
    ) {}

    public static function fromRequest(StoreFeedbackRequest|UpdateFeedbackRequest $request, int $guestId): self
    {
        return new self(
            guest_id: $guestId,
            booking_id: $request->input('booking_id'),
            service_reservation_id: $request->input('service_reservation_id'),
            rating: $request->input('rating'),
            comment: $request->input('comment')
        );
    }
}
