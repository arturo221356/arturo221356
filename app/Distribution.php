<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Distribution extends Model
{
    public function sucursales()
    {
        return $this->hasMany('App\Sucursal');
    }

    public function users()
    {
        return $this->hasManyThrough('App\User', 'App\Sucursal');
    }
    public function imeis()
    {
        return $this->hasManyThrough('App\Imei', 'App\Sucursal');
    }
    public function iccs()
    {
        return $this->hasManyThrough('App\Icc', 'App\Sucursal');
    }
    public function equipos()
    {
        return $this->hasMany('App\Equipo');
    }

}
