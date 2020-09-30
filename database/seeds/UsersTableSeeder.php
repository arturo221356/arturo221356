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
    


    //seeds Users
    $superAdmin = User::create([
      'name' => 'Super Admin User',
      'email' => 'superadmin@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $superAdmin->assignRole('super-admin');

    $admin = User::create([
      'name' => 'Admin User',
      'email' => 'admin@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $admin->assignRole('administrador');


    $supervisor = User::create([
      'name' => 'supervisor User',
      'email' => 'supervisor@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $supervisor->assignRole('supervisor');

    $vendedor = User::create([
      'name' => 'Vendedor User',
      'email' => 'vendedor@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $vendedor->assignRole('vendedor');

    $vendedor2 = User::create([
      'name' => 'Vendedor 2 User',
      'email' => 'vendedor2@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $vendedor2->assignRole('vendedor');


    $externo = User::create([
      'name' => 'externo User',
      'email' => 'externo@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,
      'inventario_propio' => true,

    ]);
    $externo->assignRole('externo');
  }
}
