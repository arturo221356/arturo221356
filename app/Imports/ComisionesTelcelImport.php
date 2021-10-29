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

            if (isset($row['iccid'])) {

                $requestIcc =  substr($row['iccid'], 0, 18);


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

                        switch ($row['concepto']) {



                            case 'Bono portabilidad prepago SEMANAL':

                                $campoComision = 'porta';

                                $iccProductID = 2;

                                // $campoFechaActivado = $row['fecha_activacion'];

                                break;
                            case 'AMIGO CHIP EXPRESS 30 DIAS':

                                $campoComision = 'n';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_acti'];

                                break;

                            case 'AMIGO CHIP EXPRESS 60 DIAS':

                                $campoComision = 'n1';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_acti'];

                                break;

                            case 'AMIGO CHIP EXPRESS 90 DIAS':

                                $campoComision = 'n2';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_acti'];

                                break;


                            case '4TA EVALUACION AMIGO CHIP EXPRESS 120 DIAS':

                                $campoComision = 'n3';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_acti'];

                                break;



                            case 'BONO ESPECIAL PORTABILIDAD  CONTRATACION PSL 200':

                                $campoComision = 'n4';

                                $iccProductID = 2;

                                // $campoFechaActivado = $row['fecha_activacion'];

                                break;
                            case 'AMIGO CHIP EXPRESS BONO ESPECIAL SIN LIMITE 2 Y 3':

                                $campoComision = 'n5';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_acti'];

                                break;

                            case 'Sim Card Express activación   CIRC GCO 93_19':

                                $campoComision = 'n6';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_activacion'];

                                break;

                            case 'Sim Card Portabilidad Prepago SEMANAL CIRC GCO 619_13':

                                $campoComision = 'n6';

                                $iccProductID = 2;



                                // $campoFechaActivado = $row['fecha_activacion'];

                                break;


                            case 'COMISION AMIGO KIT GSM':

                                $campoComision = 'n';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fec_act'];

                                break;
                            case 'BONO ADICIONAL PREPAGO DESARROLLO DE MARCA':

                                $campoComision = 'n1';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_act'];

                                break;
                            case 'LINEAS LIBRES':

                                $campoComision = 'n';

                                $iccProductID = 1;

                                // $campoFechaActivado = $row['fecha_act'];

                                break;

                            default:
                                $campoComision = 'default';
                                break;
                        }



                        if (!$icc->linea) {


                            $producto  = json_decode(json_encode(array(
                                'dn' => isset($row['celular']) ? (string)$row['celular'] : (string)$row['tel_inicial'],

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

                            $chip->activated_at = now();

                            // Date::excelToDateTimeObject($campoFechaActivado);

                            $chip->save();

                            $icc->setStatus('Vendido');
                        } else {
                            $linea = $icc->linea;

                            $arrayStatuses = ['Recargable', 'Preactiva', 'Proceso'];

                            if (in_array($linea->status, $arrayStatuses)) {

                                if (!$linea->icc_sub_product_id) {

                                    $linea->icc_sub_product_id = 30;

                                    $linea->save();
                                }

                                $linea->setStatus('Activado');

                                $chip = $linea->productoable;

                                $chip->activated_at = now();

                                // Date::excelToDateTimeObject($campoFechaActivado);

                                $chip->save();

                                $icc->setStatus('Vendido');
                            }
                        }


                        $comisionExtra = 0;

                        if (isset($linea->comisiones->porta) && $row['concepto'] == 'Bono portabilidad prepago SEMANAL') {
                            $comisionExtra = $linea->comisiones->porta;
                        }


                        $linea->comisiones()->updateOrCreate([], [

                            $campoComision => isset($row['importe']) ? $row['importe'] + $comisionExtra : 0,

                        ]);
                    }
                }
            } else {
                if (isset($row['concepto'])) {

                    switch ($row['concepto']) {

                        case 'REPORTES INFORMATIVO VOLUMEN':

                            switch ($row['periodo_evaluacion']) {
                                case '6 MESES':
                                    $campoComision = 'n7';
                                    break;
                                case '9 MESES':
                                    $campoComision = 'n8';
                                    break;
                                case '12 MESES':
                                    $campoComision = 'n9';
                                    break;
                            }

                            $linea = Linea::where('dn',isset($row['telefono']) ? $row['telefono'] : 0)->first();

                            if(isset($linea)){
                                $linea->comisiones()->updateOrCreate([], [

                                    $campoComision => isset($row['importe']) ? $row['importe']  : 0,
        
                                ]);
                            }

                            

                        break;
                    }
                }


                if (isset($row['imei'])) {
                    $imei = Imei::where('imei', $row['imei'])->withTrashed()->first();

                    if ($imei != null) {

                        $imei->comisiones()->updateOrCreate([], [

                            'n11' => isset($row['importe']) ? $row['importe'] : 0,

                        ]);
                    }
                }
            }
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
