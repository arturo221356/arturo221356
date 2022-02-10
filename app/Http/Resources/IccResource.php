<?php

namespace App\Http\Resources;

use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Inventario;

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

        $traspasos = [];
        $ventas = [];

        foreach($this->traspasos as $traspaso){

            $origen = Inventario::find($traspaso->pivot->old_inventario_id);

            $detalle = [
                'id' => $traspaso->id ?? null,
                'created_at' => isset($traspaso->created_at) ? Carbon::parse($traspaso->created_at)->format('d/m/y H:i:s' ) : null,
                'origen' =>  $origen->inventarioable->name ?? null,
                'destino' => $traspaso->inventario->inventarioable->name ?? null,
            ];
            array_push($traspasos, $detalle);
        }
        foreach($this->venta as $venta){

            

            $detalle = [
                'id' => $venta->id ?? null,
                'created_at' =>  Carbon::parse($venta->created_at)->format('d/m/y H:i:s' ),
                'inventario' =>  $venta->inventario->inventarioable->name ?? null,
                'usuario' => $venta->user->name ?? null,
                'precio_vendido'=> $venta->pivot->price ?? null,
            ];
             array_push($ventas, $detalle);
        }



        return [

            'id'  => $this->id,
            'serie'      => $this->icc,
            'inventario_id' => $this->inventario_id,
            'inventario_name' => $this->inventario->inventarioable->name,
            'company' => $this->company,
            'type' => $this->type,
            'comment'  =>  $this->comment->comment ?? null,
            'status'    => $this->status,
            // 'linea' => $this->linea ? [
            //    'dn' => $this->linea->dn,
            //    'producto' => $this->linea->product->name,
            //     'subproducto' => $this->linea->subProduct,
            // ]
            //  : null,
            'linea' => $this->linea ?? null,
            'ventas' => $ventas,
            'traspasos' => $traspasos,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/y H:i:s'),
            'preactivated_at' =>  isset($this->linea->productoable->preactivated_at) ? Carbon::parse($this->linea->productoable->preactivated_at)->diffForHumans() : null,
            

        ];
    }
}
