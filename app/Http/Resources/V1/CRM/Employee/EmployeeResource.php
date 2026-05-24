<?php

namespace App\Http\Resources\V1\CRM\Employee;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        $role = Role::where('name', $this->getRoleNames()->first())->with('permissions')->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_no,
            'email' => $this->email,
            'hotel_id' => $this->hotel_id,
            'status' => $this->status,
            'role'     =>  $role ,
            'login_histories' => $this->lastLogin
            // 'login_histories' => $this->loginHistories->map(function ($history) {
            //     return [
            //         'login_at' => $history->login_at,
            //         'logout_at' => $history->logout_at,
            //         'ip_address' => $history->ip_address,
            //         'user_agent' => $history->user_agent,
            //     ];
            // }),
        ];
    }
}

