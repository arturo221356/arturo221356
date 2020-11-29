<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChipResource extends JsonResource
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
         'inventario' => $this->linea->icc->inventario->inventarioable->name,
         'recarga monto' => $this->transaction->monto,
         'recarga mensaje' => $this->transaction->taecel_message,
         'recarga folio' => $this->transaction->taecel_folio,
         'preactivated_at' => $this->preactivated_at,
         'activated_at' => $this->activated_at,
        ];
    }
}
