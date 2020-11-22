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
        ])->usuariosAsignados()->sync([ 4, 5,3]);
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
        ])->usuariosAsignados()->sync([ 6, 2]);


        //donvic
        Inventario::create([

            'distribution_id' => 1,
            'inventarioable_id' => 7,
            'inventarioable_type' => 'App\User',

        ])->usuariosAsignados()->sync([ 3, 7]);


        //daniel
        Inventario::create([

            'distribution_id' => 1,
            'inventarioable_id' => 8,
            'inventarioable_type' => 'App\User',

        ])->usuariosAsignados()->sync([ 3, 8]);


        //ricardo
        Inventario::create([

            'distribution_id' => 1,
            'inventarioable_id' => 9,
            'inventarioable_type' => 'App\User',

        ])->usuariosAsignados()->sync([ 2, 3, 9]);


        
        //ana
        Inventario::create([

            'distribution_id' => 1,
            'inventarioable_id' => 10,
            'inventarioable_type' => 'App\User',

        ])->usuariosAsignados()->sync([ 2, 3, 10]);

        // $inventarios = Inventario::all();

        // foreach($inventarios as $inventario){
        //     $inventario->givePermissionTo('activar chip');
        // }
    }
}
