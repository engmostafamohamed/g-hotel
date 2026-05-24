<?php

namespace App\Http\Repository\V1\CRM;

use App\Models\Employee;
use App\Utils\AuthUtil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class EmployeeAuthRepository
{
    public function login(array $credentials)
    {
        Auth::shouldUse('employee'); // Ensure using the 'employee' guard
        $employee = Employee::where('email', $credentials['email'])->first();

        if (!$employee || !Hash::check($credentials['password'], $employee->password)) {
            return ['status' => 'invalid_credentials'];
        }

        $token = $employee->createToken('EmployeeAuthToken', ['*'])->accessToken;

        // Passport
        // $token = $employee->createToken('EmployeeAuthToken')->plainTextToken;

        return [
            'status' => 'success',
            'employee' => $employee,
            'token' => $token,
        ];
    }
    public function logout(Request $request): bool
    {
        try {
            $employee = Auth::guard('employee')->user();

            if ($employee) {
                // Get and delete current token
                $currentToken = $request->user('employee')->currentAccessToken();

                if ($currentToken) {
                    $currentToken->delete();
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            // \Log::error('Logout error: ' . $e->getMessage());
            return false;
        }
    }
}
