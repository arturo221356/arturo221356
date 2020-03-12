<?php

namespace App\Exports;

use App\Imei;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\ImeiResource as ImeiResource;

class ImeiExport implements FromCollection
{

    protected $sucursal;

    protected $status;

    function __construct($sucursal,$status) {
           $this->sucursal = $sucursal;
           $this->status = $status;
    }
    
   
   
   
    public function collection()
    {
        return ImeiResource::collection(Imei::where('sucursal_id','=', $this->sucursal)->whereIn('status_id',$this->status)->get());
    }
}
