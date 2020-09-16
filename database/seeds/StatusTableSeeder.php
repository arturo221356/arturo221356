<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Status::truncate();
        Status::create([
            'name' => 'Disponible',
            'type' => 'general'
        ]);

        Status::create([
            'name' => 'En transito',
            'type' => 'general'
        ]);
        Status::create(['name' => 'Vendido', 
        'type' => 'general']);
        Status::create([
            'name' => 'Incompleto',
            'type' => 'general'
        ]);

        Status::create(['name' => 'Perdido', 
        'type' => 'general']);

        

        Status::create([
            'name' => 'Exportado',
            'type' => 'icc'
        ]);
        Status::create([
            'name' => 'Alta',
            'type' => 'icc'
        ]);
        Status::create([
            'name' => 'Preactivo',
            'type' => 'icc'
        ]);
    }
}
