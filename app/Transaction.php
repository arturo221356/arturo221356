<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ["taecel", "taecel_success", "taecel_transID", "monto", "dn",'company_id','recarga_id','inventario_id'];
}
