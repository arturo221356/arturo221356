<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imei extends Model
{
    protected $fillable = ["imei","sucursal_id","equipo_id","status_id"];
    
    
    
    public function sucursal(){
        return $this->belongsTo('App\Sucursal');
    }
    public function equipo(){
        return $this->belongsTo('App\Equipo');
    }
    public function status(){
        return $this->belongsTo('App\Status');
    }
}
