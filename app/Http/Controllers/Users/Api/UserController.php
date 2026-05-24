<?php

namespace App\Http\Controllers\Users\Api;

use App\DataTransferObjects\UserDTOs\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Requests\Users\Api\UserRequest;
use App\Factories\Users\UserFactory;
use App\Http\Resources\UsersResource;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    use ValidatesRequests;
    private $user;

    function __construct(UserFactory $user, protected UserService $userService)
    {

        $this->user = $user::index();

    }

    public function index()
    {

        $users = $this->user->getAll();
        return response()->json(['data' => $users], 200);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->register(RegisterUserDTO::fromRequest(($request)));

            $token = JWTAuth::fromUser($user);

            // OTP API call

            return response()->json([
                'message' => 'Registration successful',
                'token' => $token,
                'user' => $user,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

}
