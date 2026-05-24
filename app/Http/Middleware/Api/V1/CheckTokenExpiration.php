<?php

namespace App\Http\Middleware\Api\V1;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use App\Models\Guest;
class CheckTokenExpiration
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Guest|null $guest */
        $guest = Auth::guard('guest')->user();

        if (!$guest) {
            return ApiResponse::error(__('auth.token_invalid_or_missing'), [], 401);
        }

        /** @var PersonalAccessToken|null $token */
        $token = $guest->currentAccessToken();

        if (!$token) {
            return ApiResponse::error(__('auth.token_invalid_or_missing'), [], 401);
        }

        if ($token->expires_at && $token->expires_at->isPast()) {
            $token->delete();
            return ApiResponse::error(__('auth.token_expired'), [], 401);
        }

        app()->instance('guest', $guest);

        return $next($request);
    }
}
