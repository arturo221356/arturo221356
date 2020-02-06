<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    //
protected $table = "sucursales";

protected $fillable = ["nombre_sucursal","direccion_sucursal","email_sucursal"];

public function users(){
    return $this->belongsToMany('App\User');
}
}
