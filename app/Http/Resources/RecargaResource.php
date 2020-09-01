<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecargaResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'codigo'    => $this->codigo,
            'name'  => $this->name,
            'monto' => $this->monto,
            'company'   => $this->company,
        ];
    }
}
