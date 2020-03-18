<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IccStatus extends Model
{
    protected $table = "icc_status";

    public function icc(){
        return $this->hasMany('App\Icc');
    }
}
