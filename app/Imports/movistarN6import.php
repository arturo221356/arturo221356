<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use App\Icc;
use App\Linea;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithStartRow;
// use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;
// use Illuminate\Contracts\Queue\ShouldQueue;

class movistarN6import implements ToCollection, WithStartRow
{
    use Importable, SkipsFailures, SkipsErrors;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $requestIcc =  substr($row[2], 0, 18);


            // Crea la matriz de mensajes.
            $mensajes = array(
                'unique' => 'ya existe en la base de datos.',
                'digits' => 'La Icc/DN tiene que se numerica y de 19/10 digitos'
            );

            $validationData = ['icc' => $requestIcc];

            //reglas del validador
            $validator = Validator::make($validationData, [
                'icc' => 'required',
            ], $mensajes);

            if (!$validator->fails()) {
                $icc = Icc::where('icc', 'like', $requestIcc . '%')->withTrashed()->first();

                if ($icc != null) {

                    if (!$icc->linea) {


                        if ($row[6] == 'PREPAGO' || $row[6] == 'SIM') {
                            $iccProductID = 1;
                        } else {
                            $iccProductID = 2;
                        }


                        $producto  = json_decode(json_encode(array(
                            'dn' => (string)$row[1],

                            "iccProduct" => array(
                                "id" => $iccProductID,

                            ),

                            "iccSubProduct" => array(
                                "id" => 30,
                            )
                        )));

                        $linea =  (new Linea)->newLineaWithProduct($producto, $icc->id);

                        $linea->setStatus('Activado');

                        $chip = $linea->productoable;

                        $chip = $linea->productoable;

                        $chip->activated_at = Date::excelToDateTimeObject($row[4]);

                        $chip->save();

                        $icc->setStatus('Vendido');
                    } else {
                        $linea = $icc->linea;

                        $arrayStatuses = ['Recargable', 'Preactiva', 'Proceso'];

                        if (in_array($linea->status, $arrayStatuses)) {

                            $linea->setStatus('Activado');

                            if (!$linea->icc_sub_product_id) {

                                $linea->icc_sub_product_id = 30;

                                $linea->save();
                            }

                            $chip = $linea->productoable;

                            $chip->activated_at = Date::excelToDateTimeObject($row[4]);

                            $chip->save();

                            $icc->setStatus('Vendido');
                        }
                    }





                    $linea->comisiones()->updateOrCreate([], [
                        /// comision del mes N esta en la letra K y Q de excel
                        'n' => $row[10] + $row[16],
                        /// comision del mes N1 esta en la letra M , S, W   de excel
                        'n1' => $row[12] + $row[18] + $row[22],
                        //Comision del n2 estan en las filas O , U, Y ,  AC
                        'n2' => $row[14] + $row[20] + $row[24] + $row[28],
                        //Comision del n3 estan en las filas  AA , AE, AI
                        'n3' => $row[26] + $row[30] + $row[34],
                        //Comision del n2 estan en las filas AG, AK, AO
                        'n4' => $row[32] + $row[36] + $row[40],
                        //Comision del n2 estan en las filas  AM , AQ, AU
                        'n5' => $row[38] + $row[42] + $row[46],
                        //Comision del n2 estan en las filas AS, AW ,BA
                        'n6' => $row[44] + $row[48] + $row[52],
                    ]);
                }
            }
        }
    }
    public function startRow(): int
    {
        return 4;
    }
    //    public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
