<?php

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
        IccProduct::create(['name'=>'Linea nueva prepago','company_id'=>1]);
        IccProduct::create(['name'=>'Linea nueva prepago','company_id'=>2]);
        IccProduct::create(['name'=>'Portabilidad prepago','company_id'=>1]);
        IccProduct::create(['name'=>'Portabilidad prepago','company_id'=>2]);
        IccProduct::create(['name'=>'Pospago','company_id'=>1]);
        IccProduct::create(['name'=>'Pospago','company_id'=>2]);
        IccProduct::create(['name'=>'Remplazo','company_id'=>2]);
        IccProduct::create(['name'=>'Telemarketing','company_id'=>2]);
    }
}
