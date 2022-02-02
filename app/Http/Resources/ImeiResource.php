<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use App\Inventario;

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
        $traspasos = [];
        $ventas = [];

        foreach($this->traspasos as $traspaso){

            $origen = Inventario::find($traspaso->pivot->old_inventario_id);

            $detalle = [
                'id' => $traspaso->id,
                'created_at' => Carbon::parse($traspaso->created_at)->format('d/m/y h:i:s' ),
                'origen' =>  $origen->inventarioable->name,
                'destino' => $traspaso->inventario->inventarioable->name,
            ];
            array_push($traspasos, $detalle);
        }
        foreach($this->venta as $venta){

            

            $detalle = [
                'id' => $venta->id,
                'created_at' => Carbon::parse($venta->created_at)->format('d/m/y h:i:s' ),
                'inventario' =>  $venta->inventario->inventarioable->name,
                'usuario' => $venta->user->name,
                'precio_vendido'=> $venta->pivot->price,
            ];
             array_push($ventas, $detalle);
        }

        return [
            'id'       => $this->id,
            'serie'      => $this->imei,
            'inventario_id' => $this->inventario_id,
            'inventario_name' => $this->inventario->inventarioable->name,
            'marca'    => $this->equipo->marca,
            'modelo'    => $this->equipo->modelo,
            'equipo'    => $this->equipo,
            'precio'     => $this->equipo->precio,
            'costo'     => $this->equipo->costo,
            'status'    => $this->status,
            'traspasos' => $traspasos,
            'ventas' => $ventas,
            'comision_cer' => $this->comisiones->n11 ?? null,
            'comment'  => $this->comment->comment ?? null,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s' ),
            'updated_at' => Carbon::parse($this->updated_at)->toDayDateTimeString(),
            
        ];
    
    
    }

    public $preserveKeys = false;


}
