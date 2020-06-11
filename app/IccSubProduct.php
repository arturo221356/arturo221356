<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class IccSubProduct extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "icc_sub_products";

    public function product()
    {
        return $this->belongsTo('App\IccProduct','icc_product_id');
    }
    public function iccs()
    {
        return $this->hasMany('App\icc','subproduct_id');
    }
    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
    public function precio()
    {
        return ($this->costo_sim + $this->initial_price );
    }
}
