<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    use HasFactory;

    protected $fillable = [ "user_id", "monto", "caja_id"];

    public function caja()
    {
        return $this->belongsTo('App\Caja');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
