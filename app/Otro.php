<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Otro extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ["codigo","precio","costo", "distribution_id", "nombre", "descripcion"];

    protected $dates = ['deleted_at'];


    public function inventarios()
    {
        return $this->belongsToMany('App\Inventario')->withPivot('stock');
    }

    public function sellOtro($inventarioId = 13){
        
        $inventario = $this->inventarios()->wherePivot('inventario_id',$inventarioId)->first();

        if(!isset($inventario->pivot->stock)){
            return 'no hay ni vergas';
        }

        if($inventario->pivot->stock < 1){
           $this->inventarios()->detach($inventarioId);

           return 'se detacho';
        }

        $this->inventarios()->updateExistingPivot(
            $inventarioId,
             ['stock' => $inventario->pivot->stock - 1]);

        return $this->load('inventarios');
        



    }



    protected static function booted()
    {
        static::created(function ($otro) {

            $padded = Str::padLeft($otro->id, 4, '0');

            $otro->codigo = 'ACX'.$padded;

            $otro->save();
        });

    }



}
