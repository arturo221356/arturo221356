<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ["name", "email", "curp", "rfc","referencia"];

    public function venta()
    {
        return $this->belongsTo('App\User');
    }
}
