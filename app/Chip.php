<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Chip extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ["activated_at", "preactivated_at", "transaction_id"];

    public function linea()
    {
        return $this->morphOne('App\Linea', 'productoable');
    }


    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function scopeDistributionActivatedChips($chip, $initialDate, $finalDate)
    {
        $chips = $chip
        
        ->whereBetween('activated_at',[$initialDate,$finalDate])
        ->whereHas('linea', function ($query) {
            $query->currentStatus(['Activado','Sin Saldo']);
        })
        ->whereHas('linea.icc.inventario', function ($query)  {
            $user = Auth::user(); 
            $query->where('distribution_id', $user->distribution->id);
           
            
        })
            ->orderBy('activated_at','asc')->get();

        return $chips;
    }


    public function scopeInUserInventarioActivatedChips($chip, $initialDate, $finalDate)
    {
        $chips = $chip
        
        ->whereBetween('activated_at',[$initialDate,$finalDate])
        ->whereHas('linea', function ($query) {
            $query->currentStatus(['Activado','Sin Saldo']);
        })
        ->whereHas('linea.icc.inventario', function ($query)  {
            $user = Auth::user();
            $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
            $query->whereIn('inventario_id',$inventariosIds);
           
            
        })
            ->orderBy('activated_at','asc')
            ->get();

        return $chips;
    }

    public function scopeInventarioActivatedChips($chip, $initialDate, $finalDate,$inventario_id)
    {
       

        $chips = $chip

        
        ->whereBetween('activated_at',[$initialDate,$finalDate])
        ->whereHas('linea', function ($query) {
            $query->currentStatus(['Activado','Sin Saldo']);
            
        })
        ->whereHas('linea.icc', function ($query) use ($inventario_id) {
            $query->where('inventario_id',$inventario_id);
            
        })
        ->orderBy('activated_at','asc')

            ->get();

        return $chips;
    }
}
