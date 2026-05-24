<?php

namespace App\Http\Controllers\V1\CRM\Employee;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTransferObjects\EmployeeDTOs\EmployeeDTO;
use App\Http\Requests\V1\CRM\Employee\AddEmployeeReqest;
use App\Http\Requests\V1\CRM\Employee\UpdateEmployeeRequest;
use App\Http\Requests\V1\CRM\Employee\EmployeeRequest;
use App\Http\Resources\V1\CRM\Employee\EmployeeResource;
use App\Http\Resources\V1\CRM\Employee\PaginatedEmployeeListResource;
use App\Http\Repository\V1\CRM\Employee\EmployeeRepository;
use App\Http\Resources\V1\CRM\Employee\PaginatedEmployeeResource;

class EmployeeController extends Controller
{
    protected $employee;
    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employee = $employeeRepository;
    }
    public function showEmployee($id){
        $result=$this->employee->showEmployeeRepository($id);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('employee.not_found'), [], 200);
        }
        if ($result['status'] === 'error') {
            return ApiResponse::error(__('employee.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('employee.data_fetched_successfully'),
            new EmployeeResource($result['employee']),
            200
        );
    }

    public function showAllEmployee(EmployeeRequest $request){
        $result=$this->employee->showEmployeesRepository($request);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('employee.not_found'), [], 200);
        }
        if ($result['status'] === 'error') {
            return ApiResponse::error(__('employee.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('employee.data_fetched_successfully'),
            new PaginatedEmployeeResource($result['employees']),
            200
        );
    }
    public function addEmployee(AddEmployeeReqest $request){

        $result=$this->employee->storeEmployeeRepository(EmployeeDTO::fromRequest($request));
        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('employee.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('employee.data_added_successfully'),
            [],
            201
        );
    }

    public function updateEmployee(UpdateEmployeeRequest $request, $id)
    {
        $result = $this->employee->updateEmployeeRepository(EmployeeDTO::fromRequest($request), $id);

        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('employee.not_found'), [], 200);
        }

        if ($result['status'] === 'error') {
            return ApiResponse::error(__('employee.error_happend'), [], 500);
        }

        return ApiResponse::success(
            __('employee.data_updated_successfully'),
            [],
            200);
    }

    public function deleteEmployee(EmployeeRequest $request,$id){
        $result=$this->employee->deleteEmployeeRepository($request,$id);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('employee.not_found'), [], 200);
        }

        if ($result['status'] === 'error') {
            return ApiResponse::error(__('employee.error_happend'), [], 500);
        }

        return ApiResponse::success(
            __('employee.data_deleted_successfully'),
            [],
            201
        );
    }

}
