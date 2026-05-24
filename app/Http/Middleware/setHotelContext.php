<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetHotelContext
{
    public function handle(Request $request, Closure $next)
    {
        $crmUserHotelId = null;
        $headerHotelId = $request->header('hotel-id');

        // CRM: logged-in employee (via API token or session)
        if (auth('employee')->check()) {
            $crmUserHotelId = auth('employee')->user()->hotel_id ?? null;
        } elseif (auth('web')->check()) {
            $crmUserHotelId = auth('web')->user()->hotel_id ?? null;
        }

        // If employee has a hotel_id, force it and ignore header completely
        if ($crmUserHotelId) {
            $hotelId = $crmUserHotelId;
        } elseif (auth('guest')->check()) {
            // API (guest): must provide hotel-id header if logged in as guest
            if (!$headerHotelId || !is_numeric($headerHotelId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('hotel.missing_or_invalid_header')
                ], Response::HTTP_BAD_REQUEST);
            }
            $hotelId = (int) $headerHotelId;
        } else {
            // Fallback: take header if valid, otherwise null
            $hotelId = is_numeric($headerHotelId) ? (int) $headerHotelId : null;
        }

        $request->attributes->set('hotel_id', $hotelId);

        return $next($request);
    }
}