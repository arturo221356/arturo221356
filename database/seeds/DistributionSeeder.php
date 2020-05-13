<?php

use Illuminate\Database\Seeder;
use App\Distribution;

class DistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Distribution::truncate();
        Distribution::create(['name'=>'promoviles 1']);
        Distribution::create(['name'=>'promoviles 2']);

    }
}
