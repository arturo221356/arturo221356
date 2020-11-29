<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Permiso;

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
        Permission::create(['name' => 'get inventarios']);

        Permission::create(['name' => 'all inventarios']);
        

        Permission::create(['name' => 'distribution inventarios']);
     

        Permission::create(['name' => 'full update stock']);

        Permission::create(['name' => 'update stock']);

        Permission::create(['name' => 'store stock']);

        Permission::create(['name' => 'destroy stock']);

        Permission::create(['name' => 'traspasar stock']);

        Permission::create(['name' => 'update permissions']);
        
        Permission::create(['name' => 'create equipo']);

        Permission::create(['name' => 'aceptar traspaso']);

        Permission::create(['name' => 'cancelar traspaso']);

        Permission::create(['name' => 'ver traspasos']);

        Permission::create(['name' => 'preactivar linea']);

        Permission::create(['name' => 'asignar recarga']);

        Permission::create(['name' => 'create sucursal']);

        Permission::create(['name' => 'create user']);

        Permission::create(['name' => 'update user']);

        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'create supervisor']);

        Permission::create(['name' => 'create vendedor']);

        Permission::create(['name' => 'create externo']);

        Permission::create(['name' => 'activar chip']);

        Permission::create(['name' => 'create transaction']);

        Permission::create(['name' => 'preactivar masivo']);
        

        Permission::create(['guard_name' => 'web', 'name' => 'recargar taecel']);

        //roles
        //super Admin
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo('all inventarios', 'get inventarios');


        //admin
        $administradorRole = Role::create(['name' => 'administrador']);
        $administradorRole->givePermissionTo(
            'get inventarios',
            'distribution inventarios',
            'full update stock',
            'update stock',
            'store stock',
            'destroy stock',
            'traspasar stock',
            'aceptar traspaso',
            'cancelar traspaso',
            'ver traspasos',
            'preactivar linea',
            'asignar recarga',
            'create supervisor',
            'create vendedor',
            'create externo',
            'update permissions',
            'create user',
            'update user',
            'create sucursal',
            'preactivar masivo',
            'delete user',
            'create equipo'
            
        );


        //supervisor

        $supervisorRole = Role::create(['name' => 'supervisor']);

        $supervisorRole->givePermissionTo('get inventarios', 'update stock', 'ver traspasos','create user');

        //vendedor
        $vendedorRole = Role::create(['name' => 'vendedor']);

        $vendedorRole->givePermissionTo('ver traspasos', 'aceptar traspaso');

        // externo

        $externoRole = Role::create(['name' => 'externo']);

        //activar chip agregado
        $externoRole->givePermissionTo('ver traspasos', 'aceptar traspaso');
    }
}
