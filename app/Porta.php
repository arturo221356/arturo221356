<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

use Illuminate\Support\Facades\Auth;

class Porta extends Model
{
    use HasStatuses;

    protected $fillable = ["nip", "temporal", "trafico", "transaction_id", "fvc", 'preactivated_at', 'activated_at',];

    public function linea()
    {
        return $this->morphOne('App\Linea', 'productoable');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
