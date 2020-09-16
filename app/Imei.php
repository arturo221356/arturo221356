<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Searchable\Searchable;

use Spatie\Searchable\SearchResult;

class Imei extends Model implements Searchable
{
    use SoftDeletes;

    protected $fillable = ["imei", "sucursal_id", "equipo_id", "status_id", "distribution_id"];

    protected $dates = ['deleted_at'];

    public function getSearchResult(): SearchResult
    {
        // $url = route('blogPost.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->imei,
            // $url
        );
    }

    public function inventario()
    {
        return $this->belongsTo('App\Inventario');
    }
    public function equipo()
    {
        return $this->belongsTo('App\Equipo');
    }
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    public function comment()
    {
        return $this->morphOne('App\Comment', 'commentable');
    }
    public function traspasos()
    {
        return $this->morphToMany('App\Traspaso', 'traspasoable');
    }
}
