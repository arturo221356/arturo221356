<?php

use Illuminate\Database\Seeder;

use App\Company;

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
