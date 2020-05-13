<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use App\Sucursal;
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
    User::truncate();


    User::create([
      'name' => 'Admin User',
      'email' => 'admin@1.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'role_id' => 1,
    ]);

    User::create([
      'name' => 'Admin User',
      'email' => 'admin@2.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 2,
      'role_id' => 1,
    ]);

    User::create([
      'name' => 'supervisor User',
      'email' => 'supervisor@1.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'role_id' => 2,
    ]);

    User::create([
      'name' => 'supervisor User',
      'email' => 'supervisor@2.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 2,
      'role_id' => 2,
    ]);

    User::create([
      'name' => 'seller User',
      'email' => 'seller@1.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'role_id' => 3,
    ]);
    User::create([
      'name' => 'seller User',
      'email' => 'seller@2.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 2,
      'role_id' => 3,
    ]);

    User::create([
      'name' => 'externo User',
      'email' => 'externo@1.com',
      'password' => Hash::make('password'),
      'sucursal_id' => 1,
      'role_id' => 4,
    ]);
  }
}
