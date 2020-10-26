<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

class Linea extends Model
{
    

    use HasStatuses;

    use SoftDeletes;

    protected $appends = ['status','reason'];


    protected $fillable = ["icc_id", "dn", "icc_product_id", "icc_sub_product_id"];

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

    public function getReasonAttribute()
    {
        return $this->latestStatus()->reason;
    }
 

    

















}
