<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Distribution extends Model
{
    public function sucursales()
    {
        return $this->hasMany('App\Sucursal');
    }
    public function inventarios()
    {
        return $this->hasMany('App\Inventario');
    }
    public function lineas()
    {
        $array = [];
        
        foreach($this->inventarios()->get() as $inventario){
            $lineas = $inventario->lineas()->get();
            foreach($lineas as $linea){
                array_push($array,$linea);
            }
        }

        return $array;
    }

    public function users()
    {
        return $this->hasManyThrough('App\User', 'App\Inventario');
    }
    public function imeis()
    {
        return $this->hasManyThrough('App\Imei', 'App\Inventario');
    }
    public function iccs()
    {
        return $this->hasManyThrough('App\Icc', 'App\Inventario');
    }
    public function equipos()
    {
        return $this->hasMany('App\Equipo');
    }
    public function recargas()
    {
        return $this->hasMany('App\Recarga');
    }


}
