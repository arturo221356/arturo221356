<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();

        Role::create(['name'=>'admin']);
        Role::create(['name'=>'supervisor']);
        Role::create(['name'=>'vendedor']);
        Role::create(['name'=>'cambaceo']);
        Role::create(['name'=>'externo']);
    }
}
