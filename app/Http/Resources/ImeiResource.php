<?php

namespace App\Http\Resources;
use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class ImeiResource extends JsonResource
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
            'id'       => $this->id,
            'imei'      => $this->imei,
            'sucursal'    => $this->sucursal->nombre_sucursal,
            'marca'    => $this->equipo->marca,
            'modelo'    => $this->equipo->modelo,
            'status'    => $this->status->status,
            'created_at' => Carbon::parse($this->created_at)->toDayDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDayDateTimeString(),
            'prueba' => 'boton de editar',
        ];
    
    
    }

    public $preserveKeys = false;


}
