<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $fillable = ["name", "user_id", "monto", "description", "caja_id"];

    public function caja()
    {
        return $this->belongsTo('App\Caja');
    }
    public function user(){
        return $this->belongsTo('App\User')->withTrashed();
    }
}
