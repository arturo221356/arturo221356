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
        Company::create(['name'=>'Telcel']);
        Company::create(['name'=>'Movistar']);
        Company::create(['name'=>'AT&T']);

    }
}
