<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'created_at' => $this->created_at,
            'activated_at' => $this->activated_at,
           ];
    }
}
