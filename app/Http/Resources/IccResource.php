<?php

namespace App\Http\Resources;

use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class IccResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        Carbon::setLocale('es');



        return [

            'id'  => $this->id,
            'serie'      => $this->icc,
            'inventario_id' => $this->inventario_id,
            'inventario_name' => $this->inventario->inventarioable->name,
            'company' => $this->company,
            'type' => $this->type,
            'comment'  => isset($this->comment->comment) ? $this->comment->comment : null,
            'status'    => $this->status,
            'linea' => isset($this->linea) ? $this->linea : null,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/y h:i:s'),
            'preactivated_at' =>  isset($this->linea->productoable->preactivated_at) ? Carbon::parse($this->linea->productoable->preactivated_at)->diffForHumans() : null,
            

        ];
    }
}
