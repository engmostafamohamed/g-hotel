<?php

namespace App\DataTransferObjects\UserDTOs;

use app\Http\Requests\RegisterUserRequest;

class RegisterUserDTO
{

    public function __construct(
        // public $phone_no,
        public $name,
        public $email,
        public $password,
    ) {

    }

    public static function fromRequest(RegisterUserRequest $request)
    {
        return new self(
            // phone_no: $request->phone_no,
            name: $request->name,
            email: $request->email,
            password: $request->password,
        );
    }
}