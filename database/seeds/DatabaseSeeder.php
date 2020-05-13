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
        $this->call(DistributionTableSeeder::class);
       

        
       



         $imeis = factory(App\Imei::class, 1000)->create();
         $equipo = factory(App\Equipo::class, 10)->create();
         $sucursal = factory(App\Sucursal::class, 10)->create();
         $icc = factory(App\Icc::class, 1000)->create();
    }
}
