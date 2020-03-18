<?php

use Illuminate\Database\Seeder;
use App\IccStatus;

class IccStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IccStatus::truncate();
        IccStatus::create(['status'=>'Vendido']);
        IccStatus::create(['status'=>'Perdido']);
        IccStatus::create(['status'=>'En transito']);
        IccStatus::create(['status'=>'Garantia']);
        IccStatus::create(['status'=>'Disponible']);
        IccStatus::create(['status'=>'Incompleto']);
    }
}
