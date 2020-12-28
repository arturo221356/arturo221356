<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CajasResource extends JsonResource
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
            'total' => $this->total,

            'name' => isset($this->cajable->inventarioable->name) ? $this->cajable->inventarioable->name :  $this->cajable->name,


        ];
    }
}
