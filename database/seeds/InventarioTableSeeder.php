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
        

         Inventario::create([

            'distribution_id' => 1,
            'inventarioable_id' => 1,
            'inventarioable_type' => 'App\Sucursal',
        ])->usuariosAsignados()->sync([3,4,5]);
        Inventario::create([

            'distribution_id' => 2,
            'inventarioable_id' => 2,
            'inventarioable_type' => 'App\Sucursal',
        ]);
        Inventario::create([
    
            'distribution_id' => 1,
            'inventarioable_id' => 3,
            'inventarioable_type' => 'App\Sucursal',
        ])->usuariosAsignados()->attach(1);
        Inventario::create([
          
            'distribution_id' => 2,
            'inventarioable_id' => 4,
            'inventarioable_type' => 'App\Sucursal',
        ]);
        Inventario::create([
          
            'distribution_id' => 1,
            'inventarioable_id' => 6,
            'inventarioable_type' => 'App\User',
        ])->usuariosAsignados()->sync([1,5,3]);
    }
}
