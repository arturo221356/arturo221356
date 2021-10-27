<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Otro extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ["codigo", "precio", "costo", "distribution_id", "name", "description"];

    protected $dates = ['deleted_at'];


    public function inventarios()
    {
        return $this->belongsToMany('App\Inventario')->withPivot('stock');
    }

    public function checkStock($inventarioId, $counter = 1){
        $inventario = $this->inventarios()->wherePivot('inventario_id', $inventarioId)->first();

        if (!isset($inventario->pivot->stock)) {
            return false;
        }

        if ($inventario->pivot->stock < $counter) {
            return false;
        }
        return true;
    }

    public function sellOtro($inventarioId)
    {


        $inventario = $this->inventarios()->wherePivot('inventario_id', $inventarioId)->first();


        $this->inventarios()->updateExistingPivot(
            $inventarioId,
            ['stock' => $inventario->pivot->stock - 1]
        );

        if ($inventario->pivot->stock < 2) {
            $this->inventarios()->detach($inventarioId);
            return false;
        }

        return $this->load('inventarios');
    }

    public function addToInventario($inventarioId, $cantidad)
    {

        $inventario = $this->inventarios()->wherePivot('inventario_id', $inventarioId)->first();

        if (!isset($inventario->pivot->stock)) {
            $this->inventarios()->attach($inventarioId, ['stock' => $cantidad]);
        } 
        else {
            $this->inventarios()->updateExistingPivot(
                $inventarioId,
                ['stock' => $inventario->pivot->stock + $cantidad]
            );
        }
    }



    protected static function booted()
    {
        static::created(function ($otro) {

            $padded = Str::padLeft($otro->id, 4, '0');

            $otro->codigo = 'ACX' . $padded;

            $otro->save();
        });
    }
}
