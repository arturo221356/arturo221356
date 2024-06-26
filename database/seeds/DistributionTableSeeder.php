<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Distribution;



class DistributionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Distribution::truncate();
        Distribution::create([
            'name' => 'AlarmMovil',
            'taecel_key' => 'c490127ff864a719bd89877f32a574de',
            'taecel_nip' => '0c4ae19986107edd5ebcec3c6e08a0d0'
        ]);
        Distribution::create([
            'name' => 'Promoviles',
            'taecel_key' => 'c490127ff864a719bd89877f32a574de',
            'taecel_nip' => '0c4ae19986107edd5ebcec3c6e08a0d0'

        ]);
        Distribution::create([
            'name' => 'Pruebas',
            'taecel_key' => 'c490127ff864a719bd89877f32a574de',
            'taecel_nip' => '0c4ae19986107edd5ebcec3c6e08a0d0'

        ]);
    }
}
