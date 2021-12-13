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
         'dn' => $this->dn,
         'icc' => $this->icc->icc,
         'compañia' => $this->icc->company->name,
         'tipo' => $this->icc->type->name,
         'estatus' => $this->status,
         'inventario' => $this->icc->inventario->inventarioable->name,
         'Usuario' => isset($this->user) ? $this->user->name: null,
         'Producto' => isset($this->product->name) ? $this->product->name : null,
         'Sub Producto' => isset($this->subProduct->name) ? $this->subProduct->name : null,
         'recarga monto' => isset($this->productoable->transaction->monto) ? $this->productoable->transaction->monto : 0,
         'recarga mensaje' => isset($this->productoable->transaction->taecel_message) ? $this->productoable->transaction->taecel_message : ' ',
         'recarga folio' => isset($this->productoable->transaction->taecel_folio) ? $this->productoable->transaction->taecel_folio : ' ',
         'preactivated_at' => $this->productoable->preactivated_at,
         'activated_at' => $this->productoable->activated_at,
        ];
    }
}
