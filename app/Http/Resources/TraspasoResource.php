<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Inventario;

use Illuminate\Support\Carbon;

class TraspasoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $array = [];
        
        $imeis = $this->imeis()->with('equipo')->get();
        
        $iccs = $this->iccs()->with(['type','company'])->get();

        $inventario = Inventario::findOrFail($this->inventario_id);

        $inventario_name = $inventario->inventarioable->name;

        $user_name = null;

        foreach($imeis as $imei){
           
            $old_inventario = Inventario::findOrFail($imei->pivot->old_inventario_id);
            $imei->pivot->old_inventario_name = $old_inventario->inventarioable->name;
            array_push($array,$imei);
        }
        foreach($iccs as $icc){
           
            $old_inventario = Inventario::findOrFail($icc->pivot->old_inventario_id);
            $icc->pivot->old_inventario_name = $old_inventario->inventarioable->name;
            array_push($array,$icc);
        }

        if($this->user_id){
            $user_name = $this->user->name;
            }
        
        return[
            'id' => $this->id,
            'series'=> $array,
            'destino_name' =>$inventario_name,
            'user_name' => $user_name,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s' ),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/y h:i:s' ),
            'accepted'=> $this->accepted,
        
        
        
        ];

            
    }
}
