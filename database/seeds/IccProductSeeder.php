<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\IccProduct;

class IccProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IccProduct::truncate();
        IccProduct::create(['name'=>'Linea nueva prepago',]);
        IccProduct::create(['name'=>'Portabilidad prepago',]);
        IccProduct::create(['name'=>'Pospago',]);
        IccProduct::create(['name'=>'Remplazo',]);
        IccProduct::create(['name'=>'Telemarketing',]);
    }
}
