<?php

namespace App\Http\Resources;

use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class IccResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        Carbon::setLocale('es');

        $lineaStatus = "";

        $lineaDn = "";

        $lineaId = "";

        $chipActivatedAt = null;

        $chipPreactivatedAt = null;

        if($this->status() == 'Eliminado'){
            $linea = $this->linea()->withTrashed()->first();
        }else{
            $linea = $this->linea()->first();
        }

        

        if($linea){
             $lineaStatus = $linea->status();

            $lineaDn = $linea->dn;

            $lineaId = $linea->id;


            if($linea->productoable){


                $chipActivatedAt = $linea->activated_at;

                $chipPreactivatedAt = $linea->productoable->preactivated_at;
            }
        
            
        }


        return [

            'id'       => $this->id,
            'serie'      => $this->icc,
            'inventario_id' => $this->inventario_id,
            'inventario_name' => $this->inventario->inventarioable->name,
            'company' => $this->company,
            'type' => $this->type,
            'comment'  => $this->comment,
            'status'    => $this->status,
            'linea_status'    => $lineaStatus,
            'linea_id' => $lineaId,
            'linea_dn' => $lineaDn,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/y h:i:s'),
            'preactivated_at' =>  $chipPreactivatedAt ? Carbon::parse($chipPreactivatedAt)->diffForHumans() : null,
            'activated_at' => $chipActivatedAt ? Carbon::parse($chipActivatedAt)->diffForHumans() : null,
            

        ];
    }
}
