<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Icc extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = ["icc", "sucursal_id", "status_id","distribution_id"];

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    public function subproduct()
    {
        return $this->belongsTo('App\IccSubProduct', 'sub_product_id');
    }
    public function comment()
    {
        return $this->morphOne('App\Comment', 'commentable');
    }
    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
}
