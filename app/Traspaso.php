<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{

    protected $fillable = ["sucursal_id"];


    public function iccs()
    {
        return $this->morphedByMany('App\Icc', 'traspasoable');
    }
    public function imeis()
    {
        return $this->morphedByMany('App\Imei', 'traspasoable');
    }
}
