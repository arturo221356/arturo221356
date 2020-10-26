<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remplazo extends Model
{
    public function linea()
    {
        return $this->morphOne('App\Linea', 'productoable');
    }
}
