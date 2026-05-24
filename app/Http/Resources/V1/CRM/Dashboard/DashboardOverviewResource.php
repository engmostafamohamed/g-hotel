<?php

namespace App\Http\Resources\V1\CRM\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardOverviewResource extends JsonResource
{
    public function toArray($request): array
    {
        // $this->resource is the array returned by the service
        return [
            'service_distribution' => $this->resource['service_distribution'] ?? [],
            'tier_distribution' => $this->resource['tier_distribution'] ?? [],
            'customer_segmentation' => $this->resource['customer_segmentation'] ?? [],
            'leaderboard' => $this->resource['leaderboard'] ?? [],
            'total_guests' => $this->resource['total_guests'] ?? 0,
            'active_bookings' => $this->resource['active_bookings'] ?? 0,
            'points_over_time' => $this->resource['points_over_time'] ?? [],
            'cumulative_revenue_over_time' => $this->resource['revenue_over_time'] ?? [],
            'bookings_over_time' => $this->resource['bookings_over_time'] ?? [],
        ];
    }
}
