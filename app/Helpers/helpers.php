<?php

if (! function_exists('current_hotel_id')) {
    /**
     * Get the current hotel_id from the request context.
     *
     * @return int|null
     */
    function current_hotel_id(): ?int
    {
        return request()->attributes->get('hotel_id');
    }
}