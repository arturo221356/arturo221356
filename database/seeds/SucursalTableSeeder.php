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
        Sucursal::truncate();

        Sucursal::create(['Nombre_sucursal' => 'Bodega', 'direccion_sucursal' => 'madero 110', 'email_sucursal' => 'bodega@promoviles.com', 'distribution_id' => 1]);
        Sucursal::create(['Nombre_sucursal' => 'Tlaquepaque', 'direccion_sucursal' => 'niños heroes 54', 'email_sucursal' => 'tlaqeupaque@promoviles.com', 'distribution_id' => 2]);
        Sucursal::create(['Nombre_sucursal' => 'Tonala', 'direccion_sucursal' => 'niños heroes 54', 'email_sucursal' => 'tlaqeupaque@promoviles.com', 'distribution_id' => 1]);
    }
}
