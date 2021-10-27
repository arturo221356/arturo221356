<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldOtro extends Model
{
    use HasFactory;

    protected $fillable = [ "otro_id","precio_vendido","costo"];

    public function otro(){
        return $this->belongsTo(Otro::class)->withTrashed();
    }
}
