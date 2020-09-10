<?php

use Illuminate\Database\Seeder;

use App\Recarga;

class RecargasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recarga::create([
            'name' => 'Recarga tiempo Aire 10',
            'monto' => 20,
            'codigo' => 'recarga10',
            'company_id' => 2,
          ]);

          Recarga::create([
            'name' => 'Recarga tiempo Aire 20',
            'monto' => 20,

            'codigo' => 'recarga20',
            'company_id' => 2,
          ]);
          Recarga::create([
            'name' => 'Recarga tiempo Aire 30',
            'monto' => 30,
            'codigo' => 'recarga30',
            'company_id' => 2,
          ]);
    }
}
