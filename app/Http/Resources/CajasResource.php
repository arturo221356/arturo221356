<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

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
            'lastcorte' => $this->lastcorte,
            'lastcorteDate' => isset($this->lastcorte->created_at) ? Carbon::parse($this->lastcorte->created_at)->format('d/m/y h:i:s') : '',
            'name' => isset($this->cajable->inventarioable->name) ? $this->cajable->inventarioable->name :  $this->cajable->name,


        ];
    }
}
