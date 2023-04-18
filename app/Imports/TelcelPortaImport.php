<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
use App\TelcelUser;
use App\Jobs\SubirTelcelPorta;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class TelcelPortaImport implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */

    private $apiUrl;

    public function __construct($apiUrl)
    {       


        $this->apiUrl = $apiUrl;

        
    }

    public function collection(Collection $collection)
    {
        $rows = $collection->toArray();

        foreach ($rows as $key => $row) {
            $numero = $row[1];
            $nip = $row[2];
            $icc = substr($row[0],0,19);
            $promo = '547';
            
            $telcelUser = TelcelUser::where('distribution_id', 1)->first();
            SubirTelcelPorta::dispatch($numero, $nip, $telcelUser, $this->apiUrl, $icc, $promo);

        }



    }
}
