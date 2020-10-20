<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function inventario()
    {
        return $this->belongsTo('App\Inventario');
    }

    public function comment()
    {
        return $this->morphOne('App\Comment', 'commentable');
    }
    public function generalProducts()
    {
        return $this->morphedByMany('App\ProductoGeneral', 'ventaable')->withPivot(['price']);
    }
    public function imeis()
    {
        return $this->morphedByMany('App\Imei', 'ventaable')->withPivot(['price']);
    }
    
    public function iccs()
    {
        return $this->morphedByMany('App\Icc', 'ventaable')->withPivot(['price']);
    }
    public function transactions()
    {
        return $this->morphedByMany('App\Transaction', 'ventaable')->withPivot(['price']);
    }
}
