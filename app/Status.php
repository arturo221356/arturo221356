<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = "status";


    public function imeis(){
        return $this->hasMany('App\Imei');
    }
}
