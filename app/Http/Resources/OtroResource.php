<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OtroResource extends JsonResource
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
            'codigo'       => $this->codigo,
            'name'      => $this->name,
            'description' => $this->description,
            'inventarios' => $this->inventarios,
            'precio'     => $this->precio,
            'costo'     => $this->costo,
        ];
    }
}
