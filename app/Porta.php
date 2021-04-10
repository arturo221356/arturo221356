<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

use Illuminate\Support\Facades\Auth;

class Porta extends Model
{
    use HasStatuses;
    
    protected $fillable = ["nip", "temporal", "trafico","transaction_id", "fvc", 'preactivated_at', 'activated_at',];

    public function linea()
    {
        


        return $this->morphOne('App\Linea', 'productoable');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
    




    public function scopeDistributionPortas($porta, $initialDate, $finalDate)
    {
        $portas = $porta
        
        ->whereBetween('created_at',[$initialDate,$finalDate])
        ->whereHas('linea', function ($query) {
            $query->currentStatus(['Activado','Porta subida','Preactiva','Porta Exitosa']);
        })
        ->whereHas('linea.icc.inventario', function ($query)  {
            $user = Auth::user();
            $query->where('distribution_id', $user->distribution->id);
           
            
        })
            ->orderBy('created_at','asc')->get();

        return $portas;
    }


    public function scopeInUserInventarioPortas($porta, $initialDate, $finalDate)
    {
        $portas = $porta
        
        ->whereBetween('created_at',[$initialDate,$finalDate])
        ->whereHas('linea', function ($query) {
            $query->currentStatus(['Activado','Porta subida','Preactiva','Porta Exitosa']);
        })
        ->whereHas('linea.icc.inventario', function ($query)  {
            $user = Auth::user();
            $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
            $query->whereIn('inventario_id',$inventariosIds);
           
            
        })
            ->orderBy('created_at','asc')
            ->get();

        return $portas;
    }

    public function scopeInventarioPortas($porta, $initialDate, $finalDate,$inventario_id)
    {
       

        $portas = $porta

        
        ->whereBetween('created_at',[$initialDate,$finalDate])
        ->whereHas('linea', function ($query) {
            $query->currentStatus(['Activado','Porta subida','Porta Exitosa']);
            
        })
        ->whereHas('linea.icc', function ($query) use ($inventario_id) {
            $query->where('inventario_id',$inventario_id);
            
        })
        ->orderBy('created_at','asc')

            ->get();

        return $portas;
    }
}
