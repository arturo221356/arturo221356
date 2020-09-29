<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Traits\HasRoles;

class Inventario extends Model
{
    use HasRoles;

    protected $guard_name = 'web';

    protected $table = "inventarios";

    public function inventarioable()
    {
        return $this->morphTo();
    }
    public function imeis()
    {
        return $this->hasMany('App\Imei');
    }
    public function iccs()
    {
        return $this->hasMany('App\Icc');
    }
    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
    public function lineas()
    {
        return $this->hasManyThrough('App\Linea','App\Icc');
    }
    public function usuariosAsignados()
    {
        return $this->belongsToMany('App\User');
    }



}
