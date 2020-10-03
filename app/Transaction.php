<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ["taecel", "taecel_success","taecel_timeout", "taecel_folio","taecel_message", "taecel_transID","taecel_nota","taecel_status", "monto", "dn",'company_id','recarga_id','inventario_id'];
}
