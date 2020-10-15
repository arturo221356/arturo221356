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

    // 1
    $superAdmin = User::create([
      'name' => 'Super Admin User',
      'email' => 'superadmin@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $superAdmin->assignRole('super-admin');

    // 2
    $admin = User::create([
      'name' => 'Admin User',
      'email' => 'admin@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $admin->assignRole('administrador');

    // 3
    $supervisor = User::create([
      'name' => 'supervisor User',
      'email' => 'supervisor@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $supervisor->assignRole('supervisor');


    // 4
    $vendedor = User::create([
      'name' => 'Vendedor User',
      'email' => 'vendedor@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $vendedor->assignRole('vendedor');


    // 5
    $vendedor2 = User::create([
      'name' => 'Vendedor 2 User',
      'email' => 'vendedor2@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $vendedor2->assignRole('vendedor');

    // 6
    $externo = User::create([
      'name' => 'externo User',
      'email' => 'externo@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,
      'inventario_propio' => true,

    ]);
    $externo->assignRole('externo');

    // 7
    $donVic = User::create([
      'name' => 'Don victor',
      'email' => 'donvic@promoviles.com',
      'password' => Hash::make('secreta123'),
      'distribution_id' => 1,
      'inventario_propio' => true,

    ]);

    $donVic->assignRole('externo');

    // 8
    $daniel = User::create([
      'name' => 'Daniel',
      'email' => 'daniel@promoviles.com',
      'password' => Hash::make('secreta123'),
      'distribution_id' => 1,
      'inventario_propio' => true,

    ]);

    $daniel->assignRole('externo');

    // 9
    $ricardo = User::create([
      'name' => 'Ricardo',
      'email' => 'ricardo@promoviles.com',
      'password' => Hash::make('secreta123'),
      'distribution_id' => 1,
      'inventario_propio' => true,

    ]);

    $ricardo->assignRole('externo');

    // 10
    $ana = User::create([
      'name' => 'Ana',
      'email' => 'ana@promoviles.com',
      'password' => Hash::make('secreta123'),
      'distribution_id' => 1,
      'inventario_propio' => true,

    ]);

    $ana->assignRole('externo');
  }
}
