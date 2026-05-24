<?php

namespace App\Http\Repository\V1\CRM\Employee;

use Illuminate\Http\Request;
use App\DataTransferObjects\EmployeeDTOs\EmployeeDTO;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\DataTransferObjects\RoleDTOs\RoleDTO;

interface EmployeeRepositoryInterface
{
    public function showEmployeesRepository(Request $request);
    public function showEmployeeRepository($id);

    public function storeEmployeeRepository(EmployeeDTO $request);

    public function updateEmployeeRepository(EmployeeDTO $request ,int $id);

    public function deleteEmployeeRepository(Request $request,int $id);

    // public function exists(int $id): bool;

}
