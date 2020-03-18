<?php

use Illuminate\Database\Seeder;
use App\Icc_status;

class Icc_statusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Icc_status::truncate();
        Icc_status::create(['status'=>'Vendido']);
        Icc_status::create(['status'=>'Perdido']);
        Icc_status::create(['status'=>'En transito']);
        Icc_status::create(['status'=>'Garantia']);
        Icc_status::create(['status'=>'Disponible']);
        Icc_status::create(['status'=>'Incompleto']);
    }
}
