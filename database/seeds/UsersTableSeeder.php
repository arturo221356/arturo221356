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
      'name' => 'Arturo Alvarez',
      'email' => 'arturo221355@gmail.com',
      'password' => Hash::make('password'),
      'distribution_id' => 1,

    ]);
    $admin->assignRole('administrador');


    $admin = User::create([
      'name' => 'Arturo Alvarez',
      'email' => 'Arturo_alvareze@hotmail.com',
      'password' => Hash::make('password'),
      'distribution_id' => 2,

    ]);
    $admin->assignRole('administrador');
    
    $admin = User::create([
      'name' => 'Admin de prueba',
      'email' => 'admin@promoviles.com',
      'password' => Hash::make('password'),
      'distribution_id' => 3,

    ]);
    $admin->assignRole('administrador');


    // // 3
    // $supervisor = User::create([
    //   'name' => 'supervisor de prueba',
    //   'email' => 'supervisor@promoviles.com',
    //   'password' => Hash::make('password'),
    //   'distribution_id' => 3,

    // ]);
    // $supervisor->assignRole('supervisor');


    // // 4
    // $vendedor = User::create([
    //   'name' => 'Vendedor de prueba',
    //   'email' => 'vendedor@promoviles.com',
    //   'password' => Hash::make('password'),
    //   'distribution_id' => 3,

    // ]);
    // $vendedor->assignRole('vendedor');

    // // 6
    // $externo = User::create([
    //   'name' => 'externo de prueba',
    //   'email' => 'externo@promoviles.com',
    //   'password' => Hash::make('password'),
    //   'distribution_id' => 3,
    //   'inventario_propio' => true,

    // ]);
    // $externo->assignRole('externo');

  }
}
