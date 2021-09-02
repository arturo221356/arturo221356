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
            'tipo' => $this->linea->icc->type->name,
            'estatus' => $this->linea->status,
            '' => isset($this->linea->status->reason) ? $this->linea->status->reason: null,
            'inventario' => $this->linea->icc->inventario->inventarioable->name,
            'trafico' => $this->trafico_real == true ? 'Si':'No',
            'Subida' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            'Preactiva' => isset($this->preactivated_at) ? Carbon::parse($this->preactivated_at)->format('d/m/y h:i:s') : '',
            'Activada' =>isset($this->activated_at) ? Carbon::parse($this->activated_at)->format('d/m/y h:i:s') : '',
            'recarga monto' => isset($this->transaction->monto) ? $this->transaction->monto : '',
            'recarga mensaje' => isset($this->transaction->taecel_message) ?  $this->transaction->taecel_message : '',
            'recarga folio' =>  isset($this->transaction->taecel_folio) ?  $this->transaction->taecel_folio : '',
        ]; 
    }
}
