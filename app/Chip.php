<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chip extends Model
{
    use SoftDeletes;
    
    public $timestamps = false;
    
    protected $fillable = ["activated_at", "preactivated_at","transaction_id"];

    public function linea()
    {
        
        


        return $this->morphOne('App\Linea', 'productoable');
    }


    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }
}
