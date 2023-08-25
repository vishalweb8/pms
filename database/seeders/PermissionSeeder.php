<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_list = config('module-permission');
        // Get the superAdmin role
        $role = Role::updateOrCreate(['name' => 'Super Admin','code' => 'ADMIN', 'type' => 'ADMIN']);
         $user = User::find(1);
         $user->assignRole('Super Admin');
        $permissions = [];

        foreach ($permission_list as $key => $list) {
            foreach ($list as $permission_key => $permission) {
                // Add the permission into database
                $permissions[] = Permission::updateOrCreate(['name' => $permission_key]);
            }
        }
        // Sync permission to superAdmin role
        $role->syncPermissions($permissions);

    }
}
