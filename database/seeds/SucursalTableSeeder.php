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

        Sucursal::create(['Nombre_sucursal'=>'tonala','direccion_sucursal'=>'madero 110','email_sucursal'=>'tonala@promoviles.com']);
        Sucursal::create(['Nombre_sucursal'=>'tlaquepaque','direccion_sucursal'=>'niños heroes 54','email_sucursal'=>'tlaqeupaque@promoviles.com']);
    }
}
