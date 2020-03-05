<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::truncate();
        Status::create(['status'=>'Vendido']);
        Status::create(['status'=>'Perdido']);
        Status::create(['status'=>'En transito']);
        Status::create(['status'=>'Garantia']);
        Status::create(['status'=>'Disponible']);
    }
}
