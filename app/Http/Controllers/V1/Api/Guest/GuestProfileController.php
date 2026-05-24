<?php

namespace App\Http\Controllers\V1\Api\Guest;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\V1\Api\Profile\ProfileGuestRequest;
use App\Models\Guest;
use App\Helpers\ApiResponse;
class GuestProfileController extends Controller
{
    // Show guest profile
    public function show()
    {
        /** @var Guest $guest */
        $guest = Auth::user();
        if (!$guest) {
            return ApiResponse::error(__('auth.guest_not_authenticated'), [], 401);
        }

        $guest->load([
        'loyaltyAccount.transactions' => function ($query) {
            $query->orderByDesc('created_at');
        }
    ]);

        return ApiResponse::success(__('profile.fetched_successfully'), $guest, 200);
    }

    // Update guest profile
    public function update(ProfileGuestRequest $request)
    {
        /** @var Guest $guest */
        $guest = Auth::user();

        if (!$guest) {
            return ApiResponse::error(__('auth.guest_not_authenticated'), [], 401);
        }

        $guest->update($request->validated());

        return ApiResponse::success(__('profile.updated_successfully'), $guest, 200);
    }
}
