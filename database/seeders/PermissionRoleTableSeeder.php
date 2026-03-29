<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        // User role: all permissions except user_, role_, permission_ management
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) !== 'user_'
                && substr($permission->title, 0, 5) !== 'role_'
                && substr($permission->title, 0, 11) !== 'permission_'
                && $permission->title !== 'user_management_access'
                && substr($permission->title, 0, 10) !== 'equipment_';
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions->pluck('id'));

        // User role also gets equipment_loan access + create + show
        $loanPerms = Permission::whereIn('title', [
            'equipment_loan_access', 'equipment_loan_create', 'equipment_loan_show',
        ])->pluck('id');
        Role::findOrFail(2)->permissions()->syncWithoutDetaching($loanPerms);
    }
}
