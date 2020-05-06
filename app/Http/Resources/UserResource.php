<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'       => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role->name,
            'role_id' => $this->role->id,
            'sucursal' => $this->sucursal->nombre_sucursal,
            'sucursal_id' => $this->sucursal->id,
        ];
    }
}
