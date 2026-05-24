<?php
namespace App\Services;

use App\DataTransferObjects\UserDTOs\RegisterUserDTO;
use App\Repositories\UserRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function register(RegisterUserDTO $dto)
    {
        $existing = $this->userRepository->findByEmail($dto->email);

        if ($existing) {
            throw new HttpResponseException(response()->json([
                'message' => 'Email already registered.'
            ], 409));
        }

        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            // 'phone_no' => $dto->phone_no,
            'password' => bcrypt($dto->password),
        ]);

        if (!$user) {
            throw new ValidationException('Failed Registration');
        } else {
            return $user;
        }
    }
}
