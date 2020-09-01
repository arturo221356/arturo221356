<?php

use Illuminate\Database\Seeder;

use App\IccType;

class IccTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IccType::create([
            'name' => 'Chip Express',
            'company_id' => 1
        ]);
        IccType::create([
            'name' => 'Universal',
            'company_id' => 2,
        ]);
        IccType::create([
            'name' => 'Movistar Sim',
            'company_id' => 2,
        ]);
    }
}
