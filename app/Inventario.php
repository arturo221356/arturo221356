<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = "inventarios";

    public function inventarioable()
    {
        return $this->morphTo();
    }


}
