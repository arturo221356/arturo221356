<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Traits\HasRoles;



class Inventario extends Model
{
    use HasRoles;

    

    protected $fillable = ["distribution_id"];

    protected $guard_name = 'web';

    protected $table = "inventarios";

    public function inventarioable()
    {
        return $this->morphTo()->withTrashed();
    }
    public function imeis()
    {
        return $this->hasMany('App\Imei');
    }
    public function ventas()
    {
        return $this->hasMany('App\Venta');
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
    public function caja()
    {
        return $this->morphOne(Caja::class, 'cajable');
    }
    public function otros()
    {
        return $this->belongsToMany(Otro::class)->withPivot('stock');
    }



}
