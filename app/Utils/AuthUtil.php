<?php

namespace App\Utils;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthUtil
{
    /**
     * Hash a password.
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * Compare a plain password with its hashed version.
     *
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    public static function comparePassword(string $password, string $hashedPassword): bool
    {
        return Hash::check($password, $hashedPassword);
    }

    /**
     * Generate a 6-digit numeric OTP.
     *
     * @return string
     */
    public static function generateOTP(): string
    {
        return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP to an email address.
     *
     * @param string $email
     * @param string $otp
     * @return void
     */
    public static function sendOtpToEmail(string $email, string $otp): void
    {
        $subject = 'Your OTP Code';
        $message = "Your One-Time Password (OTP) is: <strong>{$otp}</strong>";

        Mail::html($message, function ($msg) use ($email, $subject) {
            $msg->to($email)
                ->subject($subject);
        });
    }
}
