<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use App\Icc;
use App\Linea;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithStartRow;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Maatwebsite\Excel\Concerns\WithChunkReading;

class movistarN1import implements ToCollection, WithStartRow
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


                       if($row[6] == 'PREPAGO' ||$row[6] == 'SIM' ){
                           $iccProductID = 1;
                       }else{
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

                        $chip->activated_at = Date::excelToDateTimeObject($row[4]);

                        $chip->save();

                        $icc->setStatus('Vendido');


                    } else {
                        $linea = $icc->linea;

                        $arrayStatuses = ['Recargable','Preactiva','Proceso'];

                        if ( in_array($linea->status, $arrayStatuses)) {
                            

                            $linea->setStatus('Activado');

                            if(!$linea->icc_sub_product_id){
                                
                                $linea->icc_sub_product_id = 30;

                                $linea->save();
                            }

                            $chip = $linea->productoable;
    
                            $chip->activated_at = Date::excelToDateTimeObject($row[4]);
    
                            $chip->save();
    
                            $icc->setStatus('Vendido');
                           
                        }
                    }

                    if(isset($linea->comisiones->porta)){
                         $comisionPorta = $linea->comisiones->porta +  $row[8];
                       
                    }else{
                        // $comisionPorta = $row[5];
                        $comisionPorta = $row[8];
                    }



                    $linea->comisiones()->updateOrCreate([],[

                         /// comision del mes N1 esta en la letra P , V, Z   de excel

                         'n1' => $row[15] + $row[21] + $row[25],
                        
                        'porta' => $comisionPorta,
                    ]);
                }
            }
        }
    }
    public function startRow(): int
    {
        return 4;
    }
    //     public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
