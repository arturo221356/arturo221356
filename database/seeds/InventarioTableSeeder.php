<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Inventario;
use App\User;

class InventarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supervisor = User::find(3);
         Inventario::create([

            'distribution_id' => 1,
            'inventarioable_id' => 1,
            'inventarioable_type' => 'App\Sucursal',
        ])->supervisores()->attach($supervisor);
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
        Inventario::create([
          
            'distribution_id' => 1,
            'inventarioable_id' => 6,
            'inventarioable_type' => 'App\User',
        ])->supervisores()->attach($supervisor);
    }
}
