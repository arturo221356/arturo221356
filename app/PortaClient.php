<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortaClient extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'apaterno', 'amaterno','curp'];

}
