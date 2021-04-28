<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Arr;

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
        $roles = $this->roles->pluck('name');

        $inventariosData = $this->inventariosAsignados()->with('inventarioable')->get();

        $inventariosNames = Arr::pluck($inventariosData, 'inventarioable.name');

        $arrInventariosIds = Arr::pluck($inventariosData, 'id');

        if($arrInventariosIds){
            if(count($arrInventariosIds) > 1){
                $inventariosIds = $arrInventariosIds;
            }else{
                $inventariosIds  = $arrInventariosIds[0];
            }
        }else{
            $inventariosIds = null;
        }
        

        return [
            'id'       => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'inventario_propio' => $this->inventario_propio,
            'permisos' => $this->getDirectPermissions()->pluck('name'),
            'roles' => $roles,
            'inventarios' => implode(',  ',$inventariosNames),
            'inventarios_ids' =>  $inventariosIds
        ];
    }
}
