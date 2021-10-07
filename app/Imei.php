<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Searchable\Searchable;

use Spatie\Searchable\SearchResult;

use Spatie\ModelStatus\Status;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Imei extends Model implements  Searchable 
{
    use SoftDeletes;

    use HasStatuses;

    use HasFactory;
    
    protected $fillable = ["imei", "inventario_id", "equipo_id", "distribution_id"];

    protected $dates = ['deleted_at'];

    protected $appends = ['status'];

    public function getSearchResult(): SearchResult
    {
        // $url = route('blogPost.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->imei,
            // $url
        );
    }
    public function getStatus()
    {
        return $this->status;
    }

    public function inventario()
    {
        return $this->belongsTo('App\Inventario');
    }
    public function equipo()
    {
        return $this->belongsTo('App\Equipo');
    }

    public function comment()
    {
        return $this->morphOne('App\Comment', 'commentable');
    }
    public function traspasos()
    {
        return $this->morphToMany('App\Traspaso', 'traspasoable');
    }
    public function comisiones()
    {
        return $this->morphOne(Comision::class, 'comisionable');
    }
}
