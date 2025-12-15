<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'group_name' => 'package',
                'permissions' => [
                    'package.create',
                    'package.view',
                    'package.edit',
                    'package.delete',
                ]
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    'role.create',
                    'role.view',
                    'role.edit',
                    'role.delete',
                ]
            ],
        ];

        $admin = Admin::where('username', 'superadmin')->first();
        $roleSuperAdmin = $this->maybeCreateSuperAdminRole($admin);

        // Create and Assign Permissions
        foreach ($permissions as $permissionGroup) {
            $groupName = $permissionGroup['group_name'];
            
            foreach ($permissionGroup['permissions'] as $permissionName) {
                // Check if permission exists for the 'admin' guard specifically
                $permissionExist = Permission::where('name', $permissionName)
                    ->where('guard_name', 'admin')
                    ->first();
                
                if (is_null($permissionExist)) {
                    // Create permission for admin guard
                    $permission = Permission::create([
                        'name' => $permissionName,
                        'group_name' => $groupName,
                        'guard_name' => 'admin'
                    ]);
                    
                    // Assign to superadmin role
                    $roleSuperAdmin->givePermissionTo($permission);
                    $permission->assignRole($roleSuperAdmin);
                }
            }
        }

        // Assign super admin role permission to superadmin user
        if ($admin) {
            $admin->assignRole($roleSuperAdmin);
        }
    }

    private function maybeCreateSuperAdminRole($admin): Role
    {
        $roleSuperAdmin = Role::where('name', 'superadmin')
            ->where('guard_name', 'admin')
            ->first();

        if (is_null($roleSuperAdmin)) {
            $roleSuperAdmin = Role::create([
                'name' => 'superadmin', 
                'guard_name' => 'admin'
            ]);
        }

        return $roleSuperAdmin;
    }
}