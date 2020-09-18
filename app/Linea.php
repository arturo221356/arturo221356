<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

class Linea extends Model
{
    use HasStatuses;

    public function icc()
    {
        return $this->belongsTo('App\Icc');
    }
    public function productoable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo('App\IccProduct','icc_product_id');
    }
    public function subProduct()
    {
        return $this->belongsTo('App\IccSubProduct','icc_sub_product_id');
    }
}
