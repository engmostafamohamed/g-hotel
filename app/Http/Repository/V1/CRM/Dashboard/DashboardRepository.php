<?php

namespace App\Http\Repository\V1\CRM\Dashboard;

use App\DataTransferObjects\V1\CRM\Dashboard\DashboardFilterDTO;
use App\Models\{Guest, Booking, Tier, ServiceReservation, Service, RestaurantOrder, RestaurantReservation, MenuItem};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DashboardRepository
{
    /**
     * Returns array of ['service_id','service_name','count','percentage']
     */
    public function getServiceDistribution($filters)
    {
        $hotelId = current_hotel_id();

        // Step 1: Get all services for this hotel
        $allServices = Service::query()
            ->where('hotel_id', $hotelId)
            ->select('id', 'name')
            ->get();

        // Step 2: Get counts per service (confirmed reservations)
        $counts = ServiceReservation::query()
            ->join('services', 'service_reservations.service_id', '=', 'services.id')
            ->where('service_reservations.status', 'confirmed')
            ->where('services.hotel_id', $hotelId)
            ->whereNull('service_reservations.deleted_at')
            ->select('services.id', DB::raw('COUNT(service_reservations.id) as count'))
            ->groupBy('services.id')
            ->pluck('count', 'id');

        // Step 3: Total reservations for percentage
        $total = $counts->sum() ?: 1;

        // Step 4: Map all services (including zero-count ones)
        return $allServices->map(function ($service) use ($counts, $total) {
            $count = (int) ($counts[$service->id] ?? 0);

            $nameTranslations = $service->name;

            if (is_string($nameTranslations)) {
                $decoded = json_decode($nameTranslations, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $nameTranslations = $decoded;
                } else {
                    $nameTranslations = ['en' => $nameTranslations];
                }
            }

            $locale = app()->getLocale();
            $localizedName = $nameTranslations[$locale] ?? ($nameTranslations['en'] ?? 'Unnamed');

            return [
                'name' => $localizedName,
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 2) : 0,
            ];
        })->values();
    }





    /**
     * Percentages of guests by tier
     */
    public function getTierDistribution(DashboardFilterDTO $filters): array
    {
        $hotelId = current_hotel_id();

        // Step 1: Get all tiers
        $tiers = Tier::query()->select('id', 'tier_name')->get();

        // Step 2: Count guests per tier (who belong to this hotel)
        $rows = Guest::query()
            ->whereHas('bookings', fn($q) => $q->when($hotelId, fn($q2) => $q2->where('hotel_id', $hotelId)))
            ->select('tier_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('tier_id')
            ->pluck('cnt', 'tier_id');

        // Step 3: Total guests
        $total = $rows->sum() ?: 1;

        // Step 4: Map all tiers (include those with zero guests)
        return $tiers->map(function ($tier) use ($rows, $total) {
            $count = (int) ($rows[$tier->id] ?? 0);

            $tierName = is_string($tier->tier_name) ? json_decode($tier->tier_name, true) : $tier->tier_name;
            $locale = app()->getLocale();

            // Handle translations or fallback
            if (is_array($tierName)) {
                $localizedName = $tierName[$locale] ?? ($tierName['en'] ?? 'Unnamed');
            } else {
                $localizedName = $tierName ?? 'Unnamed';
            }

            return [
                'tier_id' => $tier->id,
                'tier_name' => $localizedName,
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 2) : 0,
            ];
        })->values()->toArray();
    }



    /**
     * Customer segmentation
     * - one_timers: guests with exactly 1 booking
     * - frequent: guests with more than 1 booking in any single year (>=2 in same year)
     * - once_a_year: guests who have at least one booking per year? (interpreted as guests whose bookings span exactly one distinct year)
     *
     * NOTE: Segmentation definitions can be refined later.
     */
    public function getCustomerSegmentation(DashboardFilterDTO $filters): array
    {
        $hotelId = current_hotel_id();
        $today = now()->toDateString();

        // Limit to finalized (checked_out) bookings up to today
        $baseQuery = Guest::query()->whereHas('bookings', function ($q) use ($hotelId, $today) {
            $q->where('checked_out', true)
            ->whereDate('departure_date', '<=', $today);
            if ($hotelId) {
                $q->where('hotel_id', $hotelId);
            }
        });

        $totalGuests = $baseQuery->count();

        if ($totalGuests === 0) {
            return [
                'one_timers' => 0,
                'frequent_visitors' => 0,
                'once_a_year' => 0,
                'raw_counts' => [
                    'one_timers' => 0,
                    'frequent' => 0,
                    'once_a_year' => 0,
                    'total_guests_with_finalized_bookings' => 0,
                ],
            ];
        }

        // Guests with exactly 1 finalized booking
        $oneTimers = (clone $baseQuery)
            ->withCount(['bookings as finalized_booking_count' => function ($q) use ($hotelId, $today) {
                $q->where('checked_out', true)
                ->whereDate('departure_date', '<=', $today);
                if ($hotelId) {
                    $q->where('hotel_id', $hotelId);
                }
            }])
            ->having('finalized_booking_count', '=', 1)
            ->count();

        // Frequent guests: 2+ finalized bookings in the same year
        $frequentIds = DB::table('bookings')
            ->selectRaw('guest_id, YEAR(arrival_date) as year, COUNT(*) as cnt')
            ->where('checked_out', true)
            ->whereDate('departure_date', '<=', $today)
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
            ->groupBy('guest_id', 'year')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('guest_id')
            ->unique();

        // Once-a-year guests: have >1 booking but not frequent
        $oneTimerIds = (clone $baseQuery)
            ->withCount(['bookings as finalized_booking_count' => function ($q) use ($hotelId, $today) {
                $q->where('checked_out', true)
                ->whereDate('departure_date', '<=', $today);
                if ($hotelId) {
                    $q->where('hotel_id', $hotelId);
                }
            }])
            ->having('finalized_booking_count', '=', 1)
            ->pluck('id');

        $allGuestIds = $baseQuery->pluck('id');
        $oncePerYearCount = $allGuestIds->diff($frequentIds)->diff($oneTimerIds)->count();

        return [
            'one_timers' => round(($oneTimers / $totalGuests) * 100, 2),
            'frequent_visitors' => round(($frequentIds->count() / $totalGuests) * 100, 2),
            'once_a_year' => round(($oncePerYearCount / $totalGuests) * 100, 2),
            'raw_counts' => [
                'one_timers' => $oneTimers,
                'frequent' => $frequentIds->count(),
                'once_a_year' => $oncePerYearCount,
                'total_guests_with_bookings' => $totalGuests,
            ],
        ];
    }




    /**
     * Leaderboard - top 10 guests by total_points
     */
    public function getLeaderboard(DashboardFilterDTO $filters): array
    {
        $hotelId = current_hotel_id();

        $rows = Guest::query()
            // include soft deleted guests as requested
            ->withTrashed()
            ->orderByDesc('total_points')
            ->take(10)
            ->get(['id', 'first_name', 'last_name', 'total_points']);

        return $rows->map(fn($g) => [
            'id' => $g->id,
            'name' => trim(($g->first_name ?? '') . ' ' . ($g->last_name ?? '')),
            'total_points' => (int) ($g->total_points ?? 0),
        ])->values()->toArray();
    }

    /**
     * Total guests who have at least one booking (include soft deleted)
     * include rest of party members in count?
     */
    public function getTotalGuests(DashboardFilterDTO $filters): int
    {
        $hotelId = current_hotel_id();

        $query = Guest::withTrashed()->whereHas('bookings', fn($q) => $q->when($hotelId, fn($q2) => $q2->where('hotel_id', $hotelId)));

        return (int) $query->count();
    }

    /**
     * Active bookings count (now between arrival_date and departure_date)
     */
    public function getActiveBookingsCount(DashboardFilterDTO $filters): int
    {
        $hotelId = current_hotel_id();
        $today = now()->toDateString();

        $q = Booking::query()
            ->whereDate('arrival_date', '<=', $today)
            ->whereDate('departure_date', '>=', $today)
            ->where('checked_out', false);

        if ($hotelId) $q->where('hotel_id', $hotelId);

        return (int) $q->count();
    }

    /**
     * Points earned over time (uses bookings.loyalty_points_earned for now)
     * Where are the points earned recorded? What about redeemed points? What about their dates and deadlines?
     * What about points earned from other sources such as service reservations, restaurant orders, etc.?
     * If DTO is single day -> hourly buckets, else daily buckets
     */
    public function getPointsOverTime(DashboardFilterDTO $filters): array
    {
        $hotelId = current_hotel_id();
        $from = $filters->from_date;
        $to = $filters->to_date;

        $q = Booking::query()->when($hotelId, fn($qq) => $qq->where('hotel_id', $hotelId));

        // only consider bookings that contributed points; using loyalty_points_earned column
        $q->whereBetween(DB::raw('DATE(booking_date)'), [$from, $to]);

        if ($filters->isSingleDay() || $filters->granularity === 'hour') {
            // hourly
            $rows = $q->selectRaw('HOUR(booking_time) as hour, SUM(COALESCE(loyalty_points_earned,0)) as total')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            $series = [];
            for ($h = 0; $h < 24; $h++) {
                $found = $rows->firstWhere('hour', $h);
                $series[] = [
                    'label' => sprintf('%02d:00', $h),
                    'value' => $found ? (int)$found->total : 0,
                ];
            }

            return $series;
        } else {
            // daily
            $rows = $q->selectRaw('DATE(booking_date) as date, SUM(COALESCE(loyalty_points_earned,0)) as total')
                ->groupBy(DB::raw('DATE(booking_date)'))
                ->orderBy('date')
                ->get();

            // build full date range
            $start = Carbon::parse($from);
            $end = Carbon::parse($to);
            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->addDay());

            $series = [];
            foreach ($period as $dt) {
                $d = $dt->format('Y-m-d');
                $found = $rows->firstWhere('date', $d);
                $series[] = [
                    'label' => $d,
                    'value' => $found ? (int)$found->total : 0,
                ];
            }

            return $series;
        }
    }

    /**
     * Revenue over time - combine multiple revenue sources:
     * - bookings.total_price (checked_out)
     * - service reservations -> service.price (when confirmed)
     * - restaurant orders -> menu_item.price * qty
     *
     * Isn't total_price missing from bookings table?
     * Isn't it better to store actual charged price in service_reservations instead of using service.price?
     */
    public function getRevenueOverTime(DashboardFilterDTO $filters): array
    {
        // booking revenue is calculated at departure_date time
        $hotelId = current_hotel_id();
        $from = $filters->from_date;
        $to = $filters->to_date;

        $bookingQuery = Booking::query()
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
            ->where('checked_out', true)
            ->whereBetween(DB::raw('DATE(departure_date)'), [$from, $to]);

        $series = [];

        if ($filters->isSingleDay() || $filters->granularity === 'hour') {
            // hourly cumulative revenue
            $rows = $bookingQuery
                ->selectRaw('HOUR(updated_at) as hour, SUM(COALESCE(total_price,0)) as total')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            $runningTotal = 0;

            for ($h = 0; $h < 24; $h++) {
                $found = $rows->firstWhere('hour', $h);
                $runningTotal += $found ? (int)$found->total : 0;
                $series[] = [
                    'label' => sprintf('%02d:00', $h),
                    'value' => $runningTotal,
                ];
            }
        } else {
            // daily cumulative revenue
            $rows = $bookingQuery
                ->selectRaw('DATE(departure_date) as date, SUM(COALESCE(total_price,0)) as total')
                ->groupBy(DB::raw('DATE(departure_date)'))
                ->orderBy('date')
                ->get();

            $start = Carbon::parse($from);
            $end = Carbon::parse($to);
            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->addDay());

            $runningTotal = 0;

            foreach ($period as $dt) {
                $d = $dt->format('Y-m-d');
                $found = $rows->firstWhere('date', $d);
                $runningTotal += $found ? (int)$found->total : 0;
                $series[] = [
                    'label' => $d,
                    'value' => $runningTotal,
                ];
            }
        }

        return $series;
    }



    /**
     * Bookings over time - count of checked_out bookings created in timeframe.
     * Single day -> hourly counts
     */
    public function getBookingsOverTime(DashboardFilterDTO $filters): array
    {
        // booking is counted at booking_date time which is the date of creation not date of stay
        $hotelId = current_hotel_id();
        $from = $filters->from_date;
        $to = $filters->to_date;

        $q = Booking::query()
            ->where('checked_out', true)
            ->when($hotelId, fn($qq) => $qq->where('hotel_id', $hotelId))
            ->whereBetween(DB::raw('DATE(booking_date)'), [$from, $to]);

        if ($filters->isSingleDay() || $filters->granularity === 'hour') {
            $rows = $q->selectRaw('HOUR(booking_time) as hour, COUNT(*) as cnt')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->keyBy('hour');

            $series = [];
            for ($h = 0; $h < 24; $h++) {
                $series[] = [
                    'label' => sprintf('%02d:00', $h),
                    'value' => (int) ($rows->get($h)->cnt ?? 0),
                ];
            }
            return $series;
        } else {
            $rows = $q->selectRaw('DATE(booking_date) as date, COUNT(*) as cnt')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            $start = Carbon::parse($from);
            $end = Carbon::parse($to);
            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->addDay());

            $series = [];
            foreach ($period as $dt) {
                $d = $dt->format('Y-m-d');
                $series[] = [
                    'label' => $d,
                    'value' => (int) ($rows->get($d)->cnt ?? 0),
                ];
            }
            return $series;
        }
    }
}
