<?php

namespace App\Services\V1\CRM\Feedback;

use App\Http\Repository\V1\CRM\Feedback\FeedbackRepository;

class FeedbackService
{
    public function __construct(private FeedbackRepository $repository) {}

    public function list(array $filters)
    {
        return $this->repository->index($filters);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function getFeedbackByServiceReservation(int $serviceReservationId)
    {
        return $this->repository->getByServiceReservation($serviceReservationId);
    }

    public function getFeedbackByBooking(int $bookingId)
    {
        return $this->repository->getByBooking($bookingId);
    }
}
