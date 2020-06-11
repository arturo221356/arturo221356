<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

class IccProduct extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "icc_products";

    public function subproducts()
    {
        return $this->hasMany('App\IccSubProduct','icc_product_id')->where('distribution_id', Auth::User()->distribution()->id);
    }
    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
    public function iccs()
    {
        return $this->hasOneThrough('App\Icc','App\IccSubProduct','icc_product_id','subproduct_id');
    }
}
