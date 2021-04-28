<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Permission\Traits\HasRoles;

class Sucursal extends Model
{
    use SoftDeletes;

    use HasFactory;

    use HasRoles;
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
    public function caja()
    {
        return $this->morphOne(Caja::class, 'cajable');
    }
    

}
