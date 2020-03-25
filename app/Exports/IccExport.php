<?php

namespace App\Exports;

use App\Icc;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\IccResource as IccResource;

class IccExport implements FromCollection
{
    protected $sucursal;

    protected $status;

    function __construct($sucursal,$status) {
           
           $this->sucursal = $sucursal;
           $this->status = $status;
    }
    
    
   
   
    public function collection()
    {

        if($this->sucursal == 'all'){
 
            return IccResource::collection(Icc::whereIn('status_id',$this->status)->get());

        }else{
            
            return IccResource::collection(Icc::where('sucursal_id','=', $this->sucursal)->whereIn('status_id',$this->status)->get());

        }



    }
}
