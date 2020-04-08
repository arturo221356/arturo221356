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
    public function status(){
        return $this->belongsTo('App\Status');
    }
    public function subproduct(){
        return $this->belongsTo('App\IccSubProduct','sub_product_id');
    }
    public function comment(){
        return $this->morphOne('App\Comment', 'commentable');
   
    }




}
