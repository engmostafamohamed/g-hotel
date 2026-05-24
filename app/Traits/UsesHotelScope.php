<?php

namespace App\Traits;

trait UsesHotelScope
{
    protected function getHotelIdFromAuth(): ?int
    {
        $user = auth('employee')->user();

        return $user?->hotel_id ?? null;
    }
}
