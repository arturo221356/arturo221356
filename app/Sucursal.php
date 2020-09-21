<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sucursal extends Model
{
    use SoftDeletes;

    use HasFactory;
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
