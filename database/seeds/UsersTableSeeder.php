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
    $getInventarios = Permission::create(['name' => 'get inventarios']);

    $allInventarios = Permission::create(['name' => 'all inventarios']);

    $distributionInventarios = Permission::create(['name' => 'distribution inventarios']);



    //roles
    $superAdminRole = Role::create(['name' => 'super-admin']);
    $superAdminRole->givePermissionTo($getInventarios);
    $superAdminRole->givePermissionTo('all inventarios');

    $administradorRole = Role::create(['name' => 'administrador']);
    $administradorRole->givePermissionTo($getInventarios);
    $administradorRole->givePermissionTo('distribution inventarios');

    $supervisorRole = Role::create(['name' => 'supervisor']);
    $supervisorRole->givePermissionTo($getInventarios);


    $vendedorRole = Role::create(['name' => 'vendedor']);

    $externoRole = Role::create(['name' => 'externo']);


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
      'sucursal_id' => 3,
      'distribution_id' => 1,
      
    ]);
    $vendedor = User::create([
      'name' => 'Vendedor 2 User',
      'email' => 'vendedor2@promoviles.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'distribution_id' => 1,
      
    ]);
    $vendedor->assignRole($vendedorRole);


    $externo = User::create([
      'name' => 'externo User',
      'email' => 'externo@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,
      'inventario_propio'=> true,
      
    ]);
    $externo->assignRole($externoRole);



  }
}
