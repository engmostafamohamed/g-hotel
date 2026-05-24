<?php

namespace App\Http\Resources\V1\CRM\Employee;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class EmployeeLoginResource extends JsonResource
{
    public function toArray($request)
    {
        $role = Role::where('name', $this->primary_role)->with('permissions')->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->primary_role,
            'permissions' => $role ? $role->permissions->pluck('name') : [],
            'cnic' => $this->cnic,
            'phone_number' => $this->phone_no,
            'token' => $this->token,
        ];
    }
}

