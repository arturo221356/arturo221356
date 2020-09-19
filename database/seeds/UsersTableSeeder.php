<?php

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
    $cargarInventarios = Permission::create(['name' => 'cargar inventarios']);





    //roles
    $superAdminRole = Role::create(['name' => 'super-admin']);
    $superAdminRole->givePermissionTo($cargarInventarios);

    $administradorRole = Role::create(['name' => 'administrador']);
    $administradorRole->givePermissionTo($cargarInventarios);

    $supervisorRole = Role::create(['name' => 'supervisor']);
    $supervisorRole->givePermissionTo($cargarInventarios);


    $vendedorRole = Role::create(['name' => 'vendedor']);

    $externoRole = Role::create(['name' => 'externo']);


    $superAdmin = User::create([
      'name' => 'Super Admin User',
      'email' => 'superadmin@promoviles.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'distribution_id' => 1,
      
    ]);
    $superAdmin->assignRole($superAdminRole);

    $admin = User::create([
      'name' => 'Super Admin User',
      'email' => 'admin@promoviles.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'distribution_id' => 1,
      
    ]);
    $admin->assignRole($administradorRole);


    $supervisor = User::create([
      'name' => 'supervisor User',
      'email' => 'supervisor@promoviles.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'distribution_id' => 1,
      
    ]);
    $supervisor->assignRole($supervisorRole);

    $vendedor = User::create([
      'name' => 'Vendedor User',
      'email' => 'vendedor@promoviles.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'distribution_id' => 1,
      
    ]);
    $vendedor->assignRole($vendedorRole);


    $vendedor = User::create([
      'name' => 'externo User',
      'email' => 'externo@promoviles.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'distribution_id' => 1,
      
    ]);
    $vendedor->assignRole($externoRole);



  }
}
