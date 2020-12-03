<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'inventario' => $this->icc->inventario->inventarioable->name,
            'created_at' => $this->created_at,
            'activated_at' => $this->activated_at,
           ];
    }
}
