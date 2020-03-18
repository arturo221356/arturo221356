<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IccSubProduct extends Model
{
    protected $table = "icc_subproduct";

    public function product(){
        return $this->belongsTo('App\IccProduct','icc_product_id');
    }
}
