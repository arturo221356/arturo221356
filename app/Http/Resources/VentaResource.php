<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

class VentaResource extends JsonResource
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
            'distribution'=>$this->inventario->distribution->name,
            'folio'       => $this->id,
            'inventario_name' => $this->inventario->inventarioable->name,
            'sucursal_domicilio' => $this->inventario->inventarioable->address,
            'vendedor' => $this->user->name,
            'cliente' => $this->cliente->name,
            'productosGenerales' =>$this->generalProducts,
            'total' => $this->total,
            'comment' => $this->comment ? $this->comment->comment : '',
            'imeis'  => $this->imeis()->with('equipo')->get(),
            'iccs' => $this->iccs()->with('linea', 'company', 'linea.product', 'linea.subProduct')->get(),
            'transactions' => $this->transactions()->with('recarga','company')->get(),
            'fecha' => Carbon::parse($this->created_at)->format('d/m/y h:i:s' )

        ];
    }
}
