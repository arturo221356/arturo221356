<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'marca', 'modelo', 'precio', 'costo',
    ];
    public function imeis()
    {
        return $this->hasMany('App\Imei');
    }
    public function distribution()
    {
        return $this->belongsTo('App\Distribution');
    }
}
