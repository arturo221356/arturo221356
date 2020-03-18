<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call(SucursalTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(Icc_statusSeeder::class);

         $imeis = factory(App\Imei::class, 100)->create();
         $equipo = factory(App\Equipo::class, 50)->create();
         $sucursal = factory(App\Sucursal::class, 50)->create();
         $icc = factory(App\Icc::class, 50)->create();
    }
}
