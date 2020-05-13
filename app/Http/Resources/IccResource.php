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
       


        return [

            'id'       => $this->id,
            'icc'      => $this->icc,
            'sucursal'    => $this->sucursal->name,
            'id_sucursal' =>$this->sucursal_id,
            // 'producto'    => $this->subproduct->product->name,
            // 'subproducto'    => $this->subproduct->name,
            // 'costosim'     => $this->subproduct->costo_sim,
            // 'pagoinicial'     => $this->subproduct->pago_inicial,
            // 'recargarequerida'     => $this->subproduct->recarga_required,
            // 'total'     => $this->subproduct->total,
            'comment'  => $this->comment,
            'status_id'    => $this->status->id,
            'status'    => $this->status->status,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s' ),
            'updated_at' => Carbon::parse($this->updated_at)->toDayDateTimeString(),
    
];
        










    }
}






