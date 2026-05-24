<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin' => [
                'configure rewards', 'manage staff permissions', 'audit logs access',
            ],
            'marketing' => ['campaign management'],
            'front desk' => ['booking management'],
            'read-only' => ['read-only access'],
        ];

        foreach ($roles as $role => $permissions) {
            $r = Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'employee', // ensure guard_name is set
            ]);

            foreach ($permissions as $permission) {
                $p = Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'employee',
                ]);

                $r->givePermissionTo($p);
            }
        }
    }
}
