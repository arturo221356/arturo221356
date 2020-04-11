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
        DB::table('role_user')->truncate();
        DB::table('sucursal_user')->truncate();

        $adminRole = Role::where('name','admin')->first();
        $adminSucursal = Sucursal::where('nombre_sucursal','tonala')->first();
        $supervisorRole = Role::where('name','supervisor')->first();
        $sellerRole = Role::where('name','seller')->first();
        $sellerSucursal = Sucursal::where('nombre_sucursal','tlaquepaque')->first();
        $externoRole = Role::where('name','externo')->first();

        $admin = User::create([
          'name' => 'Admin User',
          'email' => 'admin@admin.com',
          'password' => Hash::make('password')
        ]);

        $supervisor = User::create([
            'name' => 'supervisor User',
            'email' => 'supervisor@supervisor.com',
            'password' => Hash::make('password')
          ]);

          $seller = User::create([
            'name' => 'seller User',
            'email' => 'seller@seller.com',
            'password' => Hash::make('password')
          ]);

          $externo = User::create([
            'name' => 'externo User',
            'email' => 'externo@externo.com',
            'password' => Hash::make('password')
          ]);

          $admin->roles()->attach($adminRole);
          $admin->sucursal()->attach($adminSucursal);
          
          $supervisor->roles()->attach($supervisorRole);
          
          $seller->roles()->attach($sellerRole);
          $seller->sucursal()->attach($sellerSucursal);
          
          $externo->roles()->attach($externoRole);
    }
}
