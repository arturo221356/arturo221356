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



        return [

            'id'       => $this->id,
            'serie'      => $this->icc,
            'inventario_id' => $this->inventario_id,
            'inventario_name' => $this->inventario->inventarioable->name,
            'company' => $this->company->name,
            'type' => $this->type->name,
            'comment'  => $this->comment,
            'status'    => $this->status,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->toDayDateTimeString(),

        ];
    }
}
