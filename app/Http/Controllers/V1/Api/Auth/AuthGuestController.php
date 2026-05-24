<?php

namespace App\Http\Controllers\V1\Api\Auth;

use App\Http\Requests\V1\Api\Auth\RegisterGuestRequest;
use App\Http\Requests\V1\Api\Auth\LoginGuestRequest;
use App\Http\Requests\V1\Api\Auth\VerifyOTPGuestRequest;
use App\Http\Requests\V1\Api\Auth\ResendOTPGuestRequest;
use App\Http\Requests\V1\Api\Auth\ResetPasswordGuestRequest;
use App\Http\Requests\V1\Api\Auth\RequestResetPasswordGuestRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repository\V1\Api\Auth\AuthRepository;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\GuestResource;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Resources\V1\Api\Auth\LoginGuestResource;
use App\Services\V1\Api\Loyalty\LoyaltyService;
class AuthGuestController extends Controller
{
    protected $authRepository;
    protected $loyaltyService;

    public function __construct(AuthRepository $authRepository ,LoyaltyService $loyaltyServices)
    {
        $this->authRepository = $authRepository;
        $this->loyaltyService = $loyaltyServices;
    }

    public function register(RegisterGuestRequest  $request)
    {
        $data = $request->validated();
        if (in_array(null, $data, true)) {
            return ApiResponse::error(__('missing_fields'), [], 400);
        }

        $guest = $this->authRepository->registerGuest($data);
        if (!$guest) {
            return ApiResponse::error(__('email_exist'), [], 400);
        }
        // Prepare data to create loyalty account
        $loyaltyData = [
            'guest_id' => $guest->id,
            'tier_id' => $data['tier_id'] ?? null,
            'point_earned' => 0,
            'point_redeemed' => 0,
            'hotel_id' => $data['hotel_id'] ?? null,
        ];

        // Call Loyalty service with array (same as your API)
        $this->loyaltyService->addGuestToLoyaltyAccount((object)$loyaltyData);

        return ApiResponse::success(__('Guest_Registered'),null, 201);
    }

    public function login(LoginGuestRequest $request)
    {
        $credentials = $request->validated();

        if (!isset($credentials['email'], $credentials['password'])) {
            return ApiResponse::error(__('auth.invalid_credentials_format'), [], 400);
        }

        $result = $this->authRepository->loginGuest($credentials);

        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('auth.email_not_found'), [], 200);
        }

        if ($result['status'] === 'wrong_password') {
            return ApiResponse::error(__('auth.wrong_password'), [], 401);
        }

        if (!$result['guest']->is_verified) {
            return ApiResponse::error(__('auth.email_not_verified'), [], 403);
        }

        return ApiResponse::success(
            __('auth.login_success'),
            new LoginGuestResource($result['guest'], $result['token']),
            200
        );
    }

    public function verifyOTP(VerifyOTPGuestRequest $request)
    {
        $data = $request->validated();
        $result = $this->authRepository->verifyGuestOtp($data['email'], $data['otp']);
        if (!$result['status']) {
            return ApiResponse::error($result['message'], [], $result['code']);
        }

        return ApiResponse::success($result['message'], null, $result['code']);
    }

    public function resendOTP(ResendOTPGuestRequest $request)
    {
        $data = $request->validated();

        $result = $this->authRepository->sendOtp($data['email'], 'null');

        if (!$result['status']) {
            return ApiResponse::error($result['message'], [], $result['code']);
        }

        return ApiResponse::success($result['message'], null, $result['code']);
    }

    public function resetPassword(ResetPasswordGuestRequest $request)
    {
        $result= $this->authRepository->resetGuestPassword($request);
        if(!$result['status']){
            return ApiResponse::error($result['message'],null,$result['code']);
        }
        return ApiResponse::success($result['message'],null,$result['code']);

    }
    public function requestResetPassword(RequestResetPasswordGuestRequest $request)
    {
        // $data = $request->validated();
        $result = $this->authRepository->requestResetPassword($request);
        if(!$result['status']){
            return ApiResponse::error($result['message'],null,$result['code']);
        }
        return ApiResponse::success($result['message'],$result['otp'],$result['code']);
    }

    public function logout()
    {
        /** @var \App\Models\Guest $guest */
        $guest = Auth::user();

        /** @var PersonalAccessToken|null $token */
        $token = $guest?->currentAccessToken();

        if ($token) {
            $token->delete(); // Now Intelephense knows delete() exists
        }

        return ApiResponse::success(__('auth.logout_success'), null, 200);
    }
}
