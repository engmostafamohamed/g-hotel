<?php

use App\Http\Controllers\Api\V1\AuthGuestController;
use App\Http\Controllers\V1\GuestProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the G-Hotel App']);
});
