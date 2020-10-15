<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Recarga;

class RecargasTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    // 1
    Recarga::create([
      'name' => 'Recarga Movistar 10',
      'monto' => 10,
      'codigo' => 'mov010',
      'taecel_code' => 'mov010',
      'company_id' => 2,
    ]);
    // 2
    Recarga::create([
      'name' => 'Recarga Movistar 20',
      'monto' => 20,
      'codigo' => 'mov020',
      'taecel_code' => 'mov020',
      'company_id' => 2,
    ]);
    // 3
    Recarga::create([
      'name' => 'Recarga Movistar 30',
      'monto' => 30,
      'codigo' => 'mov030',
      'taecel_code' => 'mov030',
      'company_id' => 2,
    ]);
    // 4
    Recarga::create([
      'name' => 'Recarga Movistar 40',
      'monto' => 40,
      'codigo' => 'mov040',
      'taecel_code' => 'mov040',
      'company_id' => 2,
    ]);
    // 5
    Recarga::create([
      'name' => 'Recarga Movistar 50',
      'monto' => 50,
      'codigo' => 'mov050',
      'taecel_code' => 'mov050',
      'company_id' => 2,
    ]);
    // 6
    Recarga::create([
      'name' => 'Recarga Movistar 60',
      'monto' => 60,
      'codigo' => 'mov060',
      'taecel_code' => 'mov060',
      'company_id' => 2,
    ]);
    // 7
    Recarga::create([
      'name' => 'Recarga Movistar 100',
      'monto' => 100,
      'codigo' => 'mov100',
      'taecel_code' => 'mov100',
      'company_id' => 2,
    ]);
    // 8
    Recarga::create([
      'name' => 'Recarga Movistar 120',
      'monto' => 120,
      'codigo' => 'mov120',
      'taecel_code' => 'mov120',
      'company_id' => 2,
    ]);
    // 9
    Recarga::create([
      'name' => 'Recarga Movistar 150',
      'monto' => 150,
      'codigo' => 'mov150',
      'taecel_code' => 'mov150',
      'company_id' => 2,
    ]);
    // 10
    Recarga::create([
      'name' => 'Recarga Movistar 200',
      'monto' => 200,
      'codigo' => 'mov200',
      'taecel_code' => 'mov200',
      'company_id' => 2,
    ]);
    // 11
    Recarga::create([
      'name' => 'Recarga Movistar 250',
      'monto' => 250,
      'codigo' => 'mov250',
      'taecel_code' => 'mov250',
      'company_id' => 2,
    ]);
    // 12
    Recarga::create([
      'name' => 'Recarga Movistar 300',
      'monto' => 300,
      'codigo' => 'mov300',
      'taecel_code' => 'mov300',
      'company_id' => 2,
    ]);
    //13
    Recarga::create([
      'name' => 'Paquete Telcel 20',
      'monto' => 20,
      'codigo' => 'psl020',
      'taecel_code' => 'psl020',
      'company_id' => 1,
    ]);
    // 14
    Recarga::create([
      'name' => 'Paquete Telcel 30',
      'monto' => 30,
      'codigo' => 'psl030',
      'taecel_code' => 'psl030',
      'company_id' => 1,
    ]);
    // 15
    Recarga::create([
      'name' => 'Paquete Telcel 50',
      'monto' => 50,
      'codigo' => 'psl050',
      'taecel_code' => 'psl050',
      'company_id' => 1,
    ]);
    // 16
    Recarga::create([
      'name' => 'Paquete Telcel 80',
      'monto' => 80,
      'codigo' => 'psl080',
      'taecel_code' => 'psl080',
      'company_id' => 1,
    ]);
    // 17
    Recarga::create([
      'name' => 'Paquete Telcel 100',
      'monto' => 100,
      'codigo' => 'psl100',
      'taecel_code' => 'psl100',
      'company_id' => 1,
    ]);
    // 18
    Recarga::create([
      'name' => 'Paquete Telcel 150',
      'monto' => 150,
      'codigo' => 'psl150',
      'taecel_code' => 'psl150',
      'company_id' => 1,
    ]);
    // 19
    Recarga::create([
      'name' => 'Paquete Telcel 200',
      'monto' => 200,
      'codigo' => 'psl200',
      'taecel_code' => 'psl200',
      'company_id' => 1,
    ]);
    // 20
    Recarga::create([
      'name' => 'Paquete Telcel 300',
      'monto' => 300,
      'codigo' => 'psl300',
      'taecel_code' => 'psl300',
      'company_id' => 1,
    ]);

    //  21   
    Recarga::create([
      'name' => 'Paquete Telcel 500',
      'monto' => 500,
      'codigo' => 'psl500',
      'taecel_code' => 'psl500',
      'company_id' => 1,
    ]);

    Recarga::create([
      'name' => 'Recarga Telcel 10',
      'monto' => 10,
      'codigo' => 'tel010',
      'taecel_code' => 'tel010',
      'company_id' => 1,
    ]);
    Recarga::create([
      'name' => 'Recarga Telcel 50',
      'monto' => 50,
      'codigo' => 'tel050',
      'taecel_code' => 'tel050',
      'company_id' => 1,
    ]);
    Recarga::create([
      'name' => 'Recarga Telcel 100',
      'monto' => 100,
      'codigo' => 'tel100',
      'taecel_code' => 'tel100',
      'company_id' => 1,
    ]);
    Recarga::create([
      'name' => 'Recarga Telcel 150',
      'monto' => 150,
      'codigo' => 'tel150',
      'taecel_code' => 'tel150',
      'company_id' => 1,
    ]);
    Recarga::create([
      'name' => 'Recarga Telcel 200',
      'monto' => 200,
      'codigo' => 'tel200',
      'taecel_code' => 'tel200',
      'company_id' => 1,
    ]);
  }
}
