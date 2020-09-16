<?php

use Illuminate\Database\Seeder;
use App\Sucursal;

class SucursalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sucursal::truncate();

        Sucursal::create(['name' => 'Bodega', 'address' => 'madero 110', 'distribution_id' => 1]);
        Sucursal::create(['name' => 'Tlaquepaque', 'address' => 'niños heroes 54', 'distribution_id' => 2, ]);
        Sucursal::create(['name' => 'Tonala', 'address' => 'niños heroes 54', 'distribution_id' => 1 ]);
        Sucursal::create(['name' => 'Zapotlanejo', 'address' => 'hidalgoasdj', 'distribution_id' => 2]);
    }
}
