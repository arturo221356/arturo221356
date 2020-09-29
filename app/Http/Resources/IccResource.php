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
        $lineaStatus = "";

        $lineaDn = "";

        if($this->linea){
            $lineaStatus = $this->linea->status;

            $lineaDn = $this->linea->dn;
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
            'linea_dn' => $lineaDn,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->toDayDateTimeString(),

        ];
    }
}
