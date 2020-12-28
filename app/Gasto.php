<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;


    public function caja()
    {
        return $this->belongsTo('App\Caja');
    }
}
