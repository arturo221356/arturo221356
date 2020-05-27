<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recarga extends Model
{
    protected $fillable = [
        'name', 'monto',
    ];
}
