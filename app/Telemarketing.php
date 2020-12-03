<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemarketing extends Model
{
    use HasFactory;

    protected $fillable = ["activated_at", "preactivated_at"];

    public function linea()
    {
        return $this->morphOne('App\Linea', 'productoable');
    }
}
