<?php

namespace App\Http\Repository\V1\Api\Auth;

use App\Models\Guest;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use App\Utils\AuthUtil;
use Carbon\Carbon;
use Exception;
use App\Events\GuestLoggedIn;
use App\Http\Requests\V1\Api\Auth\RequestResetPasswordGuestRequest;
use App\Http\Requests\V1\Api\Auth\ResetPasswordGuestRequest;

class AuthRepository
{
    public function registerGuest($data): ?Guest {
        $email = strtolower($data['email']);

        // Check if guest exists
        if (Guest::where('email', $email)->exists()) {
            return null;
        }

        $hashedPassword = AuthUtil::hashPassword($data['password']);

        $guest = new Guest();
        $guest->first_name = $data['first_name'];
        $guest->last_name = $data['last_name'];
        $guest->country_code = $data['country_code'];
        $guest->phone_no = $data['phone_number'];
        $guest->email = $data['email'];
        $guest->password = $hashedPassword;

        // $guest->passport_or_id_num = $data['passport_or_id_num']??null;
        // $guest->passport_or_id_flag = $data['passport_or_id_flag'];
        // $guest->country_id =  $data['country_id'];
        // $guest->city_id =  $data['city_id'];

        $guest->save();

        $this->sendOtp($guest->email, 'verify_email');

        return $guest;
    }

    public function sendOtp(string $email, string $type)
    {
        $guest = Guest::where('email', $email)->first();

        if (!$guest) {
            return ['status' => false, 'code' => 200, 'message' => __('validation.email_not_found')];
        }

        if ($type === 'null') {
            $type = $guest->is_verified ? 'reset_password' : 'verify_email';
        }

        if ($type === 'verify_email' && $guest->is_verified) {
            return ['status' => false, 'code' => 400, 'message' => __('validation.email_already_verified')];
        }

        $otpCode = AuthUtil::generateOTP();
        $expiry = Carbon::now()->addMinutes(10);

        // Store OTP
        OtpCode::updateOrCreate(
            ['email' => $email, 'type' => $type],
            ['code' => $otpCode, 'expired_at' => $expiry]
        );

        // Send email
        try {
            AuthUtil::sendOtpToEmail($email, $otpCode);
            return ['status' => true, 'code' => 200, 'message' => __('validation.send_otp')];
        } catch (\Exception $e) {
            return ['status' => false, 'code' => 500, 'message' => __('EMAIL_SENDING_FAILED')];
        }
    }
    public function loginGuest(array $credentials): array|false
    {
        $guest = Guest::with(['country', 'city'])
            ->where('email', $credentials['email'])
            ->first();
        if (!$guest) {
            return ['status' => 'not_found'];
        }

        if (!Hash::check($credentials['password'], $guest->password)) {
            return ['status' => 'wrong_password'];
        }
        //save device token if provided
        if (isset($credentials['device_token']) && !empty($credentials['device_token'])) {
            $guest->deviceTokens()->updateOrCreate(
                ['token' => $credentials['device_token']],
                ['device_type' => $credentials['device_type'] ?? 'unknown']
            );
        }
        // fire event for login notification
        event(new GuestLoggedIn($guest));
        $token = $guest->createToken('guest-auth-token')->plainTextToken;

        return [
            'status' => 'success',
            'token' => $token,
            'guest' => $guest,
        ];
    }


    public function verifyGuestOtp(string $email, string $otp)
    {
        $otpCode = OtpCode::where('email', $email)
            //->where('code', $otp)
            ->where('type', 'verify_email')
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpCode) {
            return ['status' => false, 'code' => 400, 'message' =>__('auth.invalid_or_expired_otp')];
        }

        $guest = Guest::where('email', $email)->first();
        if (!$guest) {
            return ['status' => false, 'code' => 200, 'message' =>__('auth.guest_not_found')];
        }

        $guest->is_verified = true;
        $guest->save();

        $otpCode->delete(); // remove OTP after verification
        return ['status' => true, 'code' => 200, 'message' =>  __('auth.otp_verified_successfully')];
    }

    public function resetGuestPassword(ResetPasswordGuestRequest $request)
    {
        $email = $request->input('email');
        $phone = $request->input('phone_number');
        $otp = $request->input('otp');
        $newPassword = $request->input('newPassword');

        $otpCode = OtpCode::when($email,fn($query) => $query->where('email', $email))
            ->when($phone, fn($query) => $query->where('phone_no', $phone))
            ->where('type', 'reset_password')
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpCode) {
            return ['status'=>false,'code'=>400,'message'=>__('auth.invalid_or_expired_otp')];
        }

        $guest = Guest::when($email,fn($query) => $query->where('email', $email))
            ->when($phone, fn($query) => $query->where('phone_no', $phone))
            ->first();
        if (!$guest) {
            return ['status'=>false,'code'=>200,'message' => __('auth.guest_not_found')];
        }

        $guest->password = Hash::make($newPassword);
        $guest->save();

        $otpCode->delete(); // remove OTP after password reset

        return ['status'=>true,'code'=>200,'message' => __('auth.password_reset_successfully')];
    }

    public function requestResetPassword(RequestResetPasswordGuestRequest $request)
    {
        $email = $request->input('email');
        $phone_number = $request->input('phone_number');
        $guest = Guest::query();
        if ($email) {
            $guest = $guest->where('email', $email);
        } elseif ($phone_number) {
            $guest = $guest->where('phone_no', $phone_number);
        }
        $guest = $guest->first();

        if (!$guest) {
            // return response()->json([
            //     'success' => false,
            //     'message' => __('validation.email_not_found'),
            // ], 404);

            return ['status'=>false ,'code'=>200 ,'message' => __('auth.email_not_found')];
        }

        // Generate 6-digit OTP
        $otp = AuthUtil::generateOTP();

        //determine OTP Type key (email or phone_number)

        $otpkey = $email ? 'email' : 'phone_no';
        $otpvalue = $email ?? $phone_number;
        // Save OTP to the database
        OtpCode::updateOrCreate(
            [
                $otpkey => $otpvalue,
                'type' => 'reset_password'
            ],
            [
                'code' => $otp,
                'expired_at' => now()->addMinutes(10),
            ]
        );

        // Send the OTP
        // AuthUtil::sendOtpToEmail($guest->email, $otp);


        // Send OTP based on the method

        // if ($email) {
        //     AuthUtil::sendOtpToEmail($email, $otp);
        // } else {
        //     AuthUtil::sendOtpToPhone($phone, $otp);
        // }

        return ['status'=>true ,'otp'=>$otp,'code'=>200 ,'message' => __('auth.send_otp')];
    }
}
