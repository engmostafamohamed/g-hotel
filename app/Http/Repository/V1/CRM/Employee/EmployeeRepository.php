<?php

namespace App\Http\Repository\V1\CRM\Employee;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\DataTransferObjects\EmployeeDTOs\EmployeeDTO;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use App\Utils\AuthUtil;
class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function showEmployeesRepository(Request $request)
    {
        $contextHotelId = current_hotel_id();
        $requestedHotelId = $request->input('hotel_id');
        $per_page = $request->input('per_page', 10);
        if ($contextHotelId && $requestedHotelId && $contextHotelId != $requestedHotelId) {
            return[
                'status' => 'error',
                'message' => 'You are not authorized to view employees from that hotel.'
            ];
        }

        $hotelId = $contextHotelId ?? $requestedHotelId;

        $employees = Employee::where('status', 'active')
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))
            ->paginate($per_page);
        return [
            'status' => 'success',
            'employees' => $employees,
        ];

    }

    public function showEmployeeRepository($id)
    {
        $hotelId = current_hotel_id();

        $employee = Employee::where('id', $id)
            ->where('status', "active")
            ->when($hotelId, function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->first();

        if (!$employee) {
            return ['status' => 'not_found'];
        }
        return [
            'status' => 'success',
            'employee' => $employee,
        ];
    }

    public function storeEmployeeRepository(EmployeeDTO $request)
    {

        $hashedPassword = AuthUtil::hashPassword($request->password);

        $employee = new Employee();
        if ($request->hotel_id) {
            $employee->hotel_id = $request->hotel_id;
        }
        $employee->name = $request->employee_name;
        $employee->phone_no = $request->employee_phone_no;
        $employee->email = $request->employee_email;
        $employee->password = $hashedPassword;
        $employee->save();
        if ($request->role_ids) {
            $roles=Role::find($request->role_ids);
            $employee->assignRole($roles);
        }
        return [
            'status' => 'success',
        ];
    }

    public function updateEmployeeRepository(EmployeeDTO $request, $id)
    {
        try {
            $hotelId = $request->hotel_id;

            // Fetch employee that is not soft deleted
            $employee = Employee::where('id', $id)
                ->when($hotelId, function ($query) use ($hotelId) {
                    $query->where('hotel_id', $hotelId);
                })
                // ->whereNull('deleted_at') // Ensure it's not soft deleted
                ->first();


            if (!$employee) {
                return ['status' => 'not_found'];
            }

            if ($request->employee_name) {
                $employee->name = $request->employee_name;
            }

            if ($request->employee_phone_no) {
                $employee->phone_no = $request->employee_phone_no;
            }

            if ($request->employee_email) {
                $employee->email = $request->employee_email;
            }

            if ($request->hotel_id) {
                $employee->hotel_id = $request->hotel_id;
            }

            if ($request->hotel_id) {
                $employee->hotel_id = $request->hotel_id;
            }

            // Handle password update
            if ($request->password) {
                $employee->password = AuthUtil::hashPassword($request->password  );
            }
            $employee->save();
            // Handle role assignment
            if ($request->role_ids) {
                $employee->syncRoles($request->role_ids);
            }

            return ['status' => 'success'];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function deleteEmployeeRepository(Request $request,$id)
    {
        try {
            $hotelId = $request->input('hotel_id');

            // Find employee by ID and hotel_id
            $employee = Employee::where('id', $id)
                ->when($hotelId,fn($q)=>$q->where('hotel_id',$hotelId))
                ->first();
            if (!$employee) {
                return ['status' => 'not_found'];
            }

            // Check if already soft deleted
            // if ($employee->trashed()) {
            //     return ['status' => 'already_deleted'];
            // }

            $employee->delete(); // Use soft delete

            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error'];
        }
    }

}
