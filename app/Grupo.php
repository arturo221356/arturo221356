<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ["name", "address"];

    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
    public function inventario()
    {
        return $this->morphOne('App\Inventario', 'inventarioable');
    }
}

