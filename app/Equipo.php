<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class Equipo extends Model
{
    use SoftDeletes;

    use HasFactory;

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
