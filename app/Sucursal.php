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

    // public function users()
    // {
    //     return $this->inventario()->users();
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
