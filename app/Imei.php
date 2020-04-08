<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Imei extends Model
{
    use SoftDeletes;
    
    protected $fillable = ["imei","sucursal_id","equipo_id","status_id"];

    protected $dates = ['deleted_at'];
    
    
    
    public function sucursal(){
        return $this->belongsTo('App\Sucursal');
    }
    public function equipo(){
        return $this->belongsTo('App\Equipo');
    }
    public function status(){
        return $this->belongsTo('App\Status');
    }
    public function comment(){
        return $this->morphOne('App\Comment', 'commentable');

        
    }

}
