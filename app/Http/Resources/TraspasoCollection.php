<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;

use App\Inventario;

use Illuminate\Http\Resources\Json\JsonResource;

class TraspasoCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       $user_name = null;

       $inventario = Inventario::findOrFail($this->inventario_id);

       $inventario_name = $inventario->inventarioable->name;
       
        if($this->user_id){
         $user_name = $this->user->name;
         }
        
        return [

            'id'=> $this->id,
            'inventario_name'    =>$inventario_name,
            'user_name'    =>  $user_name,
            'id_inventario' =>$this->inventario_id,
            'accepted'    => $this->accepted,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s' ),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/y h:i:s' ),
            
    
];
    }
}

