<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

class ExportadaResource extends JsonResource
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
            'producto' => isset($this->product->name) ? $this->product->name : '',
            'sub producto' =>  isset($this->subProduct->name) ? $this->subProduct->name : '',
            'estatus' => $this->status,
            'Exportada a' => $this->reason,
            'inventario' => $this->icc->inventario->inventarioable->name,
            'Fecha de Activacion' => isset($this->productoable->activated_at) ? Carbon::parse($this->productoable->activated_at)->format('d/m/y h:i:s') : '',
            'Fecha de Exportacion' => isset($this->updated_at) ? Carbon::parse($this->updated_at)->format('d/m/y h:i:s') : '',
           ];
    }
}
