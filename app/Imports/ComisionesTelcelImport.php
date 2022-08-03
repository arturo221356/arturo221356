<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use App\Icc;
use App\Linea;
use App\Imei;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;



class ComisionesTelcelImport implements ToCollection, WithHeadingRow,  WithChunkReading
{

    use Importable, SkipsFailures, SkipsErrors;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (!isset($row['iccid'])) continue;

            $requestIcc =  substr($row['iccid'], 0, 18);

            $validationData = ['icc' => $requestIcc];

            // Crea la matriz de mensajes.
            $mensajes = array(
                'unique' => 'ya existe en la base de datos.',
                'digits' => 'La Icc/DN tiene que se numerica y de 19/10 digitos'
            );

            //reglas del validador
            $validator = Validator::make($validationData, [
                'icc' => 'required',
            ], $mensajes);

            if ($validator->fails()) continue;

            $icc = Icc::where('icc', 'like', $requestIcc . '%')->withTrashed()->first();

            // $fechaActivacion = $row['fecha_movimiento'] ? Carbon::createFromFormat('d-M-y',$row['fecha_movimiento'])->format('Y-m-d') : Carbon::now();

            $fechaActivacion = Carbon::now();

            // $fechaPublicacion =  $row['fecha_publicacion'] ? Carbon::createFromFormat('d/m/Y',$row['fecha_publicacion'])->format('Y-m-d') :  Carbon::now();

           

            if ($icc == null) continue;

            switch ($row['concepto']) {

                case 'COMISION INICIAL LINEAS LIBRES':

                    $campoComision = 'n';

                break;
                case 'COMISION ACTIVACIÓN POSPAGO':

                    $campoComision = 'n';

                break;
                case 'COMISION COMPLEMENTARIA LINEAS LIBRES':

                    $campoComision = 'n1';

                break;

                case 'BONO PORTABILIDAD PREPAGO':

                    $campoComision = 'porta';

                break;

                case 'AMIGO GSM EVALUACION 30 DIAS':

                    $campoComision = 'n';

                break;

                case 'COMISION CHIP EXPRESS y CHIP TU 30 DIAS':

                    $campoComision = 'n';


                break;

                case 'COMISION CHIP EXPRESS y CHIP TU 60 DIAS':

                    $campoComision = 'n1';

                break;

                case 'COMISION CHIP EXPRESS y CHIP TU 90 DIAS':

                    $campoComision = 'n2';



                break;

                case 'COMISION CHIP EXPRESS Y CHIP TU 120 DIAS':

                    $campoComision = 'n3';

                break;

                ////// falta el n4 comison contratacion psl 200


                case 'CHIP EXPRESS BONO ESPECIAL':

                    $campoComision = 'n5';

                break;

                case 'COMISION POR VOLUMEN':

                    $campoComision = 'n7';

                break;




                default:
                $campoComision = 'default';
                break;



            }

                if(!$icc->linea){

                    $iccProductID = 1;

                    if($row['movimiento'] == 'PORTIN') $iccProductID = 2;

                    if($row['segmento'] == 'POS') $iccProductID = 3;

                    $producto  = json_decode(json_encode(array(
                        'dn' => (string)$row['telefono'],

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

                    $chip->activated_at = $fechaActivacion;

                    $chip->save();

                    $icc->setStatus('Vendido');

                }else {
                    $linea = $icc->linea;

                    $arrayStatuses = ['Recargable', 'Preactiva', 'Proceso'];

                    if (in_array($linea->status, $arrayStatuses)) {

                        if (!$linea->icc_sub_product_id) {

                            $linea->icc_sub_product_id = 30;

                            $linea->save();
                        }

                        $linea->setStatus('Activado');

                        $chip = $linea->productoable;

                        $chip->activated_at = $fechaActivacion;

                        $chip->save();

                        $icc->setStatus('Vendido');
                    }
                }

                    $importe = $row['monto'];

               

                    if (isset($linea->comisiones->porta) && $row['concepto']=='BONO PORTABILIDAD PREPAGO') $importe += $linea->comisiones->porta;

                    if (isset($linea->comisiones->n1) && $row['concepto']=='COMISION COMPLEMENTARIA LINEAS LIBRES') $importe += $linea->comisiones->n1;

                    if (isset($linea->comisiones->n7) && $row['concepto']=='COMISION POR VOLUMEN') $importe += $linea->comisiones->n7;

                    if (isset($linea->comisiones->n) && $row['concepto']=='COMISION ACTIVACIÓN POSPAGO') $importe += $linea->comisiones->n;
                    

                

                    $linea->comisiones()->updateOrCreate([], [

                        $campoComision => $importe,
    
                    ]);

                


                 



        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
