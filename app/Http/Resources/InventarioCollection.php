<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;


use Illuminate\Http\Resources\Json\JsonResource;

class InventarioCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        
        return [

            
            'imeis'=>$this->imeis

            
    
];
    }
}
