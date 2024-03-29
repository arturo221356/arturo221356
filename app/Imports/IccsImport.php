<?php

namespace App\Imports;

use App\Icc;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class IccsImport implements ToCollection
{
    use Importable, SkipsFailures;

    private $data;

    private $errores =[];

    private $exitosos =[];

    private $mensajes = array(
        'unique' => 'ya existe en la base de datos.',
        'digits' => 'La serie tiene que se numerica y de 19 digitos'
    );



    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function collection(Collection $rows)
    {




        $rows = $rows->toArray();

        // iterating each row and validating it:
        foreach ($rows as $key => $row) {
            $serie = [];

            $formatedSerie = substr($row[0],0,19);

            $serie = ['serie' => $formatedSerie];

            $icc = new Icc([
                'icc' => $formatedSerie,
                'inventario_id' => $this->data['inventario_id'],
                'icc_type_id' => $this->data['icc_type_id'],
                'company_id' => $this->data['company_id'],
               

            ]);

            // Crea la matriz de mensajes.
            $mensajes = array(
                'unique' => 'ya existe en la base de datos.',
                'digits' => 'La serie tiene que se numerica y de 19 digitos'
            );

            //reglas del validador
            $validator = Validator::make($serie, [
                'serie' => 'required|unique:iccs,icc|digits:19',



            ], $mensajes);
            
            //agregar la serie y el mensaje de error al array 
            if ($validator->fails()) {

                $err = [];

                $errorList = [];

                $err['serie'] = $formatedSerie;



                foreach ($validator->errors()->toArray() as $error) {



                    foreach ($error as $sub_error) {
                        array_push($errorList, $sub_error);
                    }
                }
                $err['errores'] = $errorList;
                array_push($this->errores, $err);
            } else {
                
                $icc->save();
               
                array_push($this->exitosos, $serie);
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
