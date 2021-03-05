<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

class CorteResource extends JsonResource
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
            'user' => $this->user->name,
            'monto' => $this->monto,
            'restante' => $this->restante,
            'fecha' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            
            

        ];
    }
}
