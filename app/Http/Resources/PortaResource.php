<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

class PortaResource extends JsonResource
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
            'id' => $this->id,
            'dn' => $this->linea->dn,
            'icc' => $this->linea->icc->icc,
            'compañia' => $this->linea->icc->company->name,
            'estatus' => $this->linea->status,
            'inventario' => $this->linea->icc->inventario->inventarioable->name,
            'Subida' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            'Preactiva' => Carbon::parse($this->preactivated_at)->format('d/m/y h:i:s'),
            'Activada' => Carbon::parse($this->activated_at)->format('d/m/y h:i:s'),
            'recarga monto' => isset($this->transaction->monto) ? $this->transaction->monto : '',
            'recarga mensaje' => isset($this->transaction->taecel_message) ?  $this->transaction->taecel_message : '',
            'recarga folio' =>  isset($this->transaction->taecel_folio) ?  $this->transaction->taecel_folio : '',
        ]; 
    }
}
