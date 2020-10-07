<?php
namespace Database\Seeders;

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
        Permission::create(['name' => 'get inventarios']);
    
        Permission::create(['name' => 'all inventarios']);
    
        Permission::create(['name' => 'distribution inventarios']);
    
        Permission::create(['name' => 'full update stock']);
    
        Permission::create(['name' => 'update stock']);
    
        Permission::create(['name' => 'store stock']);
    
        Permission::create(['name' => 'destroy stock']);
    
        Permission::create(['name' => 'traspasar stock']);
    
        Permission::create(['name' => 'aceptar traspaso']);
    
        Permission::create(['name' => 'cancelar traspaso']);
    
        Permission::create(['name' => 'ver traspasos']);
    
        Permission::create(['name' => 'preactivar linea']);

        Permission::create(['name' => 'asignar recarga']);
    
        Permission::create(['guard_name' => 'web','name' => 'activar chip']);
    
        Permission::create(['guard_name' => 'web','name' => 'recargar taecel']);
    
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
           'asignar recarga'
        );
    
    
        //supervisor
    
        $supervisorRole = Role::create(['name' => 'supervisor']);
        
        $supervisorRole->givePermissionTo('get inventarios','update stock','ver traspasos');
    
        //vendedor
        $vendedorRole = Role::create(['name' => 'vendedor']);
    
        $vendedorRole->givePermissionTo('ver traspasos','aceptar traspaso');
    
        // externo
    
        $externoRole = Role::create(['name' => 'externo']);
    
        $externoRole->givePermissionTo('ver traspasos','aceptar traspaso');
    
    }
}
