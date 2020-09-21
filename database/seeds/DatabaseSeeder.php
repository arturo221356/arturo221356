<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Equipo;

use App\Icc;

use App\Imei;


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
        $this->call(UsersTableSeeder::class);
        $this->call(DistributionTableSeeder::class);
        $this->call(IccProductSeeder::class);
        $this->call(RecargasTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(IccTypeTableSeeder::class);
        $this->call(InventarioTableSeeder::class);

        
         Equipo::factory()->count(10)->create();

         Icc::factory()->times(10)->create();

         Imei::factory()->times(10)->create();

    }
}
