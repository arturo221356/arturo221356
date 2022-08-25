<?php

namespace App\Imports;

use App\PortaClient;
use Maatwebsite\Excel\Concerns\ToModel;

class PortaClientsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PortaClient([
            'nombre'     => $row[0],
            'apaterno'    => $row[1], 
            'amaterno'    => $row[2], 
            'curp'    => $row[3],                 
        ]);
    }
}
