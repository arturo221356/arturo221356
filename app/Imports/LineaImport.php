<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use App\Icc;
use App\Chip;
use Illuminate\Support\Facades\Auth;

class LineaImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    use Importable, SkipsFailures;

    private $errores = [];

    private $exitosos = [];


    public function collection(Collection $rows)
    {
        $user = Auth::user();

        foreach ($rows as $row) {



            $requestIcc =  substr($row[0], 0, 19);

            $dn =  substr($row[1], 0, 10);

            
            // Crea la matriz de mensajes.
            $mensajes = array(
                'unique' => 'ya existe en la base de datos.',
                'digits' => 'La Icc/DN tiene que se numerica y de 19/10 digitos'
            );

            $activacion = ['serie'=>$requestIcc,'dn'=>$dn];

            //reglas del validador
            $validator = Validator::make($activacion, [
                'serie' => 'required|digits:19',
                'dn' => 'required|digits:10'


            ], $mensajes);

            if ($validator->fails()) {

                $err = [];

                $errorList = [];

                $err['icc'] = $requestIcc;

                $err['dn'] = $dn;

                foreach ($validator->errors()->toArray() as $error) {



                    foreach ($error as $sub_error) {
                        array_push($errorList, $sub_error);
                    }
                }
                $err['errores'] = $errorList;
                array_push($this->errores, $err);

            } else {


                if ($user->can('distribution inventarios')) {

                    $icc = Icc::iccInUserDistribution($requestIcc)->first();
                } else {
                    $icc = Icc::iccInUserInventario($requestIcc)->first();
                }
                //si el icc ya tiene linea acitva, aqui falta meter al array de errores 
                if ($icc != null) {
                    if ($icc->linea()->first() != null) {
                        
                        $err['icc'] = $requestIcc;

                        $err['dn'] = $dn;
    
                        $errorList = [];
                        
                        $message = "Icc ya cuenta con linea activa ".$icc->linea->dn;
    
                        array_push($errorList, $message);
    
                        $err['errores'] = $errorList;
                        
                        array_push($this->errores, $err);

                    } else {
                       $exitoso = ['icc'=>$icc->icc, 'dn'=>$dn];

                         array_push($this->exitosos, $exitoso);

                        $chip = Chip::create([]);

                        $linea = $chip->linea()->create([
                            'dn' => $dn,
                            'icc_product_id' => 1,
                            'icc_id' => $icc->id,

                        ]);
                        $linea->setStatus('Preactivo');
                    }
                }else{
                    $err['icc'] = $requestIcc;

                    $err['dn'] = $dn;

                    $errorList = [];

                    $message = "Icc no encontrado en la base de datos";

                    array_push($errorList, $message);

                    $err['errores'] = $errorList;
                    
                    array_push($this->errores, $err);

                }
            }
        }
    }





    public function getErrors()
    {
        return $this->errores;
    }
    public function getsuccess()
    {
        return $this->exitosos;
    }
}
