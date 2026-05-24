<?php

namespace App\Services\V1\CRM\Dashboard;

use App\DataTransferObjects\V1\CRM\Dashboard\DashboardFilterDTO;
use App\Http\Repository\V1\CRM\Dashboard\DashboardRepository;
use Illuminate\Auth\Access\AuthorizationException;

class DashboardService
{
    public function __construct(private DashboardRepository $repository) {}

    /**
     * Orchestrate fetching all dashboard parts.
     *
     * @return array
     * @throws AuthorizationException
     */
    public function getOverview(DashboardFilterDTO $filters): array
    {
        // repository will validate hotel context via current_hotel_id()
        $hotelId = current_hotel_id();
        if (!$hotelId) {
            // consistent with your other modules
            throw new AuthorizationException(__('room.hotel_context_required'));
        }

        // call repository for each metric
        $serviceDistribution = $this->repository->getServiceDistribution($filters);
        $tierDistribution    = $this->repository->getTierDistribution($filters);
        $customerSeg         = $this->repository->getCustomerSegmentation($filters);
        $leaderboard         = $this->repository->getLeaderboard($filters);
        $totalGuests         = $this->repository->getTotalGuests($filters);
        $activeBookings      = $this->repository->getActiveBookingsCount($filters);
        $pointsOverTime      = $this->repository->getPointsOverTime($filters);
        $revenueOverTime     = $this->repository->getRevenueOverTime($filters);
        $bookingsOverTime    = $this->repository->getBookingsOverTime($filters);

        return [
            'service_distribution' => $serviceDistribution,
            'tier_distribution'    => $tierDistribution,
            'customer_segmentation'=> $customerSeg,
            'leaderboard'          => $leaderboard,
            'total_guests'         => $totalGuests,
            'active_bookings'      => $activeBookings,
            'points_over_time'     => $pointsOverTime,
            'revenue_over_time'    => $revenueOverTime,
            'bookings_over_time'   => $bookingsOverTime,
        ];
    }
}