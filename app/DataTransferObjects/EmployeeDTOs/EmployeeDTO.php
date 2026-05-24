<?php

namespace App\DataTransferObjects\EmployeeDTOs;

use App\Http\Requests\V1\CRM\Employee\AddEmployeeReqest;
use App\Http\Requests\V1\CRM\Employee\UpdateEmployeeRequest;

class EmployeeDTO
{
    public function __construct(
        public ?string $employee_name,
        public ?string $employee_email,
        public ?int $employee_phone_no,
        public ?int $hotel_id,
        public ?array $role_ids,
        public ?string $password,
    ) {}

    public static function fromRequest(AddEmployeeReqest|UpdateEmployeeRequest $request): self
    {
        return new self(
            employee_name: $request->input('name'),
            employee_email: $request->input('email'),
            employee_phone_no: $request->input('phone_number'),
            hotel_id: $request->input('hotel_id'),
            role_ids: $request->input('role_ids',[]),
            password: $request->input('password')
        );
    }
}
