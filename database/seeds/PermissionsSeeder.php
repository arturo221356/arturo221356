<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //permisions






        //roles
        $superAdmin = Role::create(['name' => 'super-admin']);

        $administrador = Role::create(['name' => 'administrador']);

        $supervisor = Role::create(['name' => 'supervisor']);

        $vendedor = Role::create(['name' => 'vendedor']);

        $externo = Role::create(['name' => 'externo']);
    }
}
