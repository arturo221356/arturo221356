<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Searchable\Searchable;

use Spatie\Searchable\SearchResult;



class Icc extends Model implements Searchable
{
    use SoftDeletes;

    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $dates = ['deleted_at'];

    protected $fillable = ["icc", "inventario_id", "status_id", "distribution_id", "company_id", "icc_type_id"];

    public function getSearchResult(): SearchResult
    {
        // $url = route('blogPost.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->icc,
            // $url
        );
    }

    public function inventario()
    {
        return $this->belongsTo('App\Inventario');
    }
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function subproduct()
    {
        return $this->belongsTo('App\IccSubProduct', 'subproduct_id');
    }
    public function comment()
    {
        return $this->morphOne('App\Comment', 'commentable');
    }
    public function details()
    {
        return $this->hasOne('App\IccDetail')->withDefault();
    }
    public function type()
    {
        return $this->belongsTo('App\IccType', 'icc_type_id');
    }
    public function traspasos()
    {
        return $this->morphToMany('App\Traspaso', 'traspasoable');
    }
}
