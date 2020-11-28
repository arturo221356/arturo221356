<?php

namespace Database\Seeders;

use App\Company;

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create(['name'=>'Telcel','code'=>188]);
        Company::create(['name'=>'Movistar','code'=>118]);
        Company::create(['name'=>'AT&T']);

    }
}
