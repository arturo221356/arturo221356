<?php

namespace App;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function inventario()
    {
        return $this->belongsTo('App\Inventario');
    }

    public function comment()
    {
        return $this->morphOne('App\Comment', 'commentable');
    }
    public function generalProducts()
    {
        return $this->morphedByMany('App\ProductoGeneral', 'ventaable')->withPivot(['price']);
    }
    public function imeis()
    {
        return $this->morphedByMany('App\Imei', 'ventaable')->withPivot(['price']);
    }
    
    public function iccs()
    {
        return $this->morphedByMany('App\Icc', 'ventaable')->withPivot(['price']);
    }
    public function transactions()
    {
        return $this->morphedByMany('App\Transaction', 'ventaable')->withPivot(['price']);
    }
    public function cliente()
    {
        return $this->hasOne('App\Cliente');
    }


    public function scopeDistributionVentas($venta, $initialDate, $finalDate)
    {
        $ventas = $venta
        
        ->whereBetween('created_at',[$initialDate,$finalDate])
        ->whereHas('inventario', function ($query)  {
            $user = Auth::user();
            $query->where('distribution_id', $user->distribution->id);
           
            
        })
            ->orderBy('created_at','asc')->get();

        return $ventas;
    }

    public function scopeVentaInInventario($venta, $initialDate, $finalDate,$inventario_id)
    {
        $ventas = $venta
        
        ->whereBetween('created_at',[$initialDate,$finalDate])

        ->whereHas('inventario', function ($query) use ($inventario_id)  {
            $user = Auth::user();
            $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
            $query->whereIn('inventario_id',$inventariosIds)
            ->where('inventario_id',$inventario_id)
            ;
           
            
        })

            ->orderBy('created_at','asc')
            ->get();

        return $ventas;
    }
}
