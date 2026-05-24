<?php

namespace App\Services\V1\Api\Feedback;

use App\DataTransferObjects\V1\Api\Feedback\FeedbackDTO;
use App\Http\Repository\V1\Api\Feedback\FeedbackRepository;
use App\Models\Feedback;

class FeedbackService
{
    public function __construct(private FeedbackRepository $repository) {}

    public function create(FeedbackDTO $dto): Feedback
    {
        return $this->repository->create($dto);
    }

    public function update(Feedback $feedback, FeedbackDTO $dto): Feedback
    {
        return $this->repository->update($feedback, $dto);
    }

    public function list($guestId, array $filters)
    {
        return $this->repository->index($guestId, $filters);
    }

    public function find(int $id, $guestId)
    {
        return $this->repository->find($id, $guestId);
    }

    public function getFeedbackByServiceReservation(int $serviceReservationId, $guestId)
    {
        return $this->repository->getByServiceReservation($serviceReservationId, $guestId);
    }

    public function getFeedbackByBooking(int $bookingId, $guestId)
    {
        return $this->repository->getByBooking($bookingId, $guestId);
    }
}
