<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'marca', 'modelo', 'precio','costo','precio_promocion',
    ];
}
