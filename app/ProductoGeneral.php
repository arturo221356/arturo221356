<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoGeneral extends Model
{
    use HasFactory;

    protected $fillable = ["price", "description", "name"];
}
