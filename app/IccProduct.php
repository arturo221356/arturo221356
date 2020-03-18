<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IccProduct extends Model
{
    protected $table = "icc_product";

    public function iccs(){
        return $this->hasMany('App\Icc');
    }
    public function subproducts(){
        return $this->hasMany('App\IccSubProduct');
    }
    public function product(){
        return $this->hasOneThrough('App\IccProduct','App\IccSubProduct');
    }



}
