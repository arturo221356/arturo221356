<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    use HasFactory;

    public function caja()
    {
        return $this->belongsTo('App\Caja');
    }
}
