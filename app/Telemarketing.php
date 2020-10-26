<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemarketing extends Model
{
    use HasFactory;

    public function linea()
    {
        return $this->morphOne('App\Linea', 'productoable');
    }
}
