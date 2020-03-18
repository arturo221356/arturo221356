<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Icc_status extends Model
{
    public function imeis(){
        return $this->hasMany('App\Icc');
    }
}
