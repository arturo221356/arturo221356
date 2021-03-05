<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

class IncomeResource extends JsonResource
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
            'nombre' => $this->name,
            'descripcion' => $this->description,
            'monto' => $this->monto,
            'usuario' => $this->user->name,
            'fecha' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            
            

        ];
    }
}
