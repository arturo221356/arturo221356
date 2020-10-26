<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

class Porta extends Model
{
    use HasStatuses;
    
    protected $fillable = ["nip", "temporal", "trafico","transaction_id", "fvc"];

    public function linea()
    {
        


        return $this->morphOne('App\Linea', 'productoable');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
