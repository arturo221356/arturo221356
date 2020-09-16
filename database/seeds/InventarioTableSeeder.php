<?php

use Illuminate\Database\Seeder;
use App\Inventario;

class InventarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inventario::create([

            'distribution_id' => 1,
            'inventarioable_id' => 1,
            'inventarioable_type' => 'App\Sucursal',
        ]);
        Inventario::create([

            'distribution_id' => 2,
            'inventarioable_id' => 2,
            'inventarioable_type' => 'App\Sucursal',
        ]);
        Inventario::create([
    
            'distribution_id' => 1,
            'inventarioable_id' => 3,
            'inventarioable_type' => 'App\Sucursal',
        ]);
        Inventario::create([
          
            'distribution_id' => 2,
            'inventarioable_id' => 4,
            'inventarioable_type' => 'App\Sucursal',
        ]);
    }
}
