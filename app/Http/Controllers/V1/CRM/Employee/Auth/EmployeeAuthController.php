<?php

namespace App\Http\Controllers\V1\CRM\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Http\Repository\V1\CRM\EmployeeAuthRepository;
use App\Http\Requests\V1\CRM\Employee\LoginRequest;
use App\Http\Resources\V1\CRM\Employee\EmployeeLoginResource;
use App\Events\EmployeeLoggedIn;
use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class EmployeeAuthController extends Controller
{
    protected $auth;

    public function __construct(EmployeeAuthRepository $auth)
    {
        $this->auth = $auth;
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->auth->login($request->validated());
            if ($result['status'] === 'invalid_credentials') {
                return response()->json([
                    'success' => false,
                    'statusCode' => '401',
                    'message' => 'Invalid credentials'
                ], 401);
            }
            // Fire login event correctly
            event(new EmployeeLoggedIn($result['employee']));
            $result['employee']->token = $result['token'];
            // Log the successful login
            return response()->json([
                'success' => true,
                'statusCode' => '200',
                'message' => 'Login successful',
                'data' => new EmployeeLoginResource($result['employee']),
            ], 200);

        } catch (Exception $e) {
            // \Log::error('Employee login error', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => "Login failed.",
                'errors' => $e->getMessage(),
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }

    }
    public function logout(Request $request)  // Add Request parameter
    {
        $result = $this->auth->logout($request);  // Pass request to repository

        if ($result) {
            return ApiResponse::success(__('Logout_successful'), null, 200);
        }

        return ApiResponse::error(__('Logout_failed'), null, 400);  // Changed from 401 to 400
    }
}
