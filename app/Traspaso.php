<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Traspaso extends Model
{
    use SoftDeletes;

    protected $fillable = ["sucursal_id","aceptacion_required","accepted","user_id","distribution_id"];


    public function iccs()
    {
        return $this->morphedByMany('App\Icc', 'traspasoable')->withPivot(['old_status_id','old_sucursal_id','old_sucursal_name']);
    }
    public function imeis()
    {
        return $this->morphedByMany('App\Imei', 'traspasoable')->withPivot(['old_status_id','old_sucursal_id','old_sucursal_name']);
    }
    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
    }
    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }



}
