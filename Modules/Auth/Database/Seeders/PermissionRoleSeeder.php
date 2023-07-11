<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);
        Permission::create(['name' => 'view-users']);
        Permission::create(['name' => 'create-jobCard']);
        Permission::create(['name' => 'create-company']);

        $superAdminRole = Role::create(['name' => 'superAdmin']);
        $AdminRole = Role::create(['name' => 'Admin']);
        $StaffManagerRole = Role::create(['name' => 'staffManager']);
        $StewardRole = Role::create(['name' => 'Steward']);


        $superAdminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'view-users',
            'create-company',
            'create-jobCard'
        ]);

        $AdminRole->givePermissionTo([
            'create-users',
            'view-users',
            'create-company',
        ]);

        $StaffManagerRole->givePermissionTo([
            'edit-users',
            'view-users',
            'create-jobCard'
        ]);

        $StewardRole->givePermissionTo([
            'view-users',
        ]);

     // $this->call("OthersTableSeeder");
    }
}
