<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pospago extends Model
{
    protected $fillable = ["activated_at", "preactivated_at"];

    public function linea()
    {
        return $this->morphOne('App\Linea', 'productoable');
    }
}
