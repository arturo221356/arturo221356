<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
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

    Permission::create(['guard_name' => 'web','name' => 'activar chip']);

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



    //seeds Users
    $superAdmin = User::create([
      'name' => 'Super Admin User',
      'email' => 'superadmin@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $superAdmin->assignRole($superAdminRole);

    $admin = User::create([
      'name' => 'Admin User',
      'email' => 'admin@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $admin->assignRole($administradorRole);


    $supervisor = User::create([
      'name' => 'supervisor User',
      'email' => 'supervisor@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $supervisor->assignRole($supervisorRole);

    $vendedor = User::create([
      'name' => 'Vendedor User',
      'email' => 'vendedor@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $vendedor->assignRole($vendedorRole);

    $vendedor2 = User::create([
      'name' => 'Vendedor 2 User',
      'email' => 'vendedor2@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $vendedor2->assignRole($vendedorRole);


    $externo = User::create([
      'name' => 'externo User',
      'email' => 'externo@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,
      'inventario_propio' => true,

    ]);
    $externo->assignRole($externoRole);
  }
}
