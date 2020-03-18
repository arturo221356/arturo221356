<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Icc extends Model
{
    protected $dates = ['deleted_at'];
    
    public function sucursal(){
        return $this->belongsTo('App\Sucursal');
    }
    public function icc_status(){
        return $this->belongsTo('App\Icc_status');
    }

}
