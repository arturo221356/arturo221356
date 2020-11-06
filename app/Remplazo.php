<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remplazo extends Model
{
    protected $fillable = [
        'preactivated_at', 'activated_at',
    ];

    public function linea()
    {
        
        return $this->morphOne('App\Linea', 'productoable');
    }
}
