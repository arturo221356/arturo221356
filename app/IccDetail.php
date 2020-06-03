<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class IccDetail extends Model
{
    use SoftDeletes;
    
    protected $fillable = ["dn","dn_temporal"];

        public function icc()
    {
        return $this->belongsTo('App\Icc');

        
    }
}
