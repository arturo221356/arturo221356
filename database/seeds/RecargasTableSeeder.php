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
            'distribution_id' => 1,
            'codigo' => 'recarga10',
          ]);

          Recarga::create([
            'name' => 'Recarga tiempo Aire 20',
            'monto' => 20,
            'distribution_id' => 1,
            'codigo' => 'recarga20',
          ]);
          Recarga::create([
            'name' => 'Recarga tiempo Aire 30',
            'monto' => 30,
            'distribution_id' => 1,
            'codigo' => 'recarga30',
          ]);
    }
}
