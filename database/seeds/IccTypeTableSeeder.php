<?php
namespace Database\Seeders;

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
            'company_id' => 1,
            'costo' => 30
        ]);
        IccType::create([
            'name' => 'Universal',
            'company_id' => 2,
            'costo' => 30
        ]);
        IccType::create([
            'name' => 'Movistar Sim',
            'company_id' => 2,
            'costo' => 30
        ]);
    }
}
