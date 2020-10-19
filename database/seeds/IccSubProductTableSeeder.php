<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\IccSubProduct;

class IccSubProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        IccSubProduct::create(['name'=>'Chip Movistar 80',
        'company_id'=>2,
        'distribution_id' =>1,
        'icc_product_id' =>1,
        'precio'=>80,
        'recarga_required'=>true,
        'recarga_id'=>5
        ]);

        IccSubProduct::create(['name'=>'Chip telcel sin saldo',
        'company_id'=>1,
        'distribution_id' =>1,
        'icc_product_id' =>1,
        'precio'=>30,
        'recarga_required'=>false,
        'recarga_id'=> null
        ]);

        IccSubProduct::create(['name'=>'Chip movistar sin saldo',
        'company_id'=>2,
        'distribution_id' =>1,
        'icc_product_id' =>1,
        'precio'=>30,
        'recarga_required'=>false,
        'recarga_id'=> null
        ]);

        IccSubProduct::create(['name'=>'Chip movistar libre',
        'company_id'=>2,
        'distribution_id' =>1,
        'icc_product_id' =>1,
        'precio'=>100,
        'recarga_required'=>true,
        'recarga_id'=> null
        ]);
        IccSubProduct::create(['name'=>'Chip telcel libre',
        'company_id'=>2,
        'distribution_id' =>1,
        'icc_product_id' =>1,
        'precio'=>100,
        'recarga_required'=>true,
        'recarga_id'=> null
        ]);

        IccSubProduct::create(['name'=>'Chip telcel 80 ',
        'company_id'=>1,
        'distribution_id' =>1,
        'icc_product_id' =>1,
        'precio'=>30,
        'recarga_required'=>true,
        'recarga_id'=>15
        ]);

        IccSubProduct::create(['name'=>'vas a volar 10',
        'company_id'=>2,
        'distribution_id' =>1,
        'icc_product_id' =>3,
        'precio'=>200,
        

        ]);

        IccSubProduct::create(['name'=>'plan telcel 1',
        'company_id'=>1,
        'distribution_id' =>1,
        'icc_product_id' =>3,
        'precio'=>400,
        
        ]);

        IccSubProduct::create(['name'=>'remplazo',
        'company_id'=>2,
        'distribution_id' =>1,
        'icc_product_id' =>4,
        'precio'=>100,
        
        ]);


        IccSubProduct::create(['name'=>'telemarketing',
        'company_id'=>2,
        'distribution_id' =>1,
        'icc_product_id' =>5,
        'precio'=>100,
        
        ]);
    }
}
