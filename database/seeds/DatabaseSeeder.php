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
        $this->call(DistributionTableSeeder::class);
        $this->call(IccProductSeeder::class);
        $this->call(RecargasTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(IccTypeTableSeeder::class);
        $this->call(InventarioTableSeeder::class);

        
       



         $imeis = factory(App\Imei::class, 1000)->create()->each(function($imei){
             $imei->setStatus('Disponible');
         });
         $equipo = factory(App\Equipo::class, 10)->create();
        //  $sucursal = factory(App\Sucursal::class, 10)->create();
         $icc = factory(App\Icc::class, 1000)->create()->each(function($icc){
            $icc->setStatus('Disponible');
        });
    }
}
