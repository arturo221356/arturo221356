<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model
{
    use SoftDeletes;
    //
    protected $table = "sucursales";

    protected $fillable = ["name", "address", "email"];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    // public function imeis()
    // {
    //     return $this->hasMany('App\Imei');
    // }
    // public function iccs()
    // {
    //     return $this->hasMany('App\Icc');
    // }
    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
    public function inventario()
    {
        return $this->morphOne('App\Inventario', 'inventarioable');
    }
}
