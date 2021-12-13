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
            'dn' => $this->dn,
            'icc' => $this->icc->icc,
            'compañia' => $this->icc->company->name,
            'tipo' => $this->icc->type->name,
            'estatus' => $this->status,
            '' => isset($this->status->reason) ? $this->status->reason: null,
            'inventario' => $this->icc->inventario->inventarioable->name,
            'Usuario' => isset($this->user) ? $this->user->name: null,
            'Producto' => isset($this->product->name) ? $this->product->name : null,
            'Sub Producto' => isset($this->subProduct->name) ? $this->subProduct->name : null,
            'trafico' => $this->productoable->trafico_real == true ? 'Si':'No',
            'Subida' => Carbon::parse($this->productoable->created_at)->format('d/m/y h:i:s'),          
            'Preactiva' => isset($this->productoable->preactivated_at) ? Carbon::parse($this->productoable->preactivated_at)->format('d/m/y h:i:s') : '',
            'Activada' =>isset($this->activated_at) ? Carbon::parse($this->productoable->activated_at)->format('d/m/y h:i:s') : '',
            'recarga monto' => isset($this->productoable->transaction->monto) ? $this->productoable->transaction->monto : '',
            'recarga mensaje' => isset($this->productoable->transaction->taecel_message) ?  $this->productoable->transaction->taecel_message : '',
            'recarga folio' =>  isset($this->productoable->transaction->taecel_folio) ?  $this->productoable->transaction->taecel_folio : '',
        ]; 
    }
}
