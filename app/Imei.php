<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imei extends Model
{
    public function sucursal(){
        return $this->belongsTo('App\Sucursal');
    }
    public function equipo(){
        return $this->belongsTo('App\Equipo');
    }
}
