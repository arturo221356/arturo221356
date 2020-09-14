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

    protected $fillable = ["icc", "sucursal_id", "status_id", "distribution_id", "company_id", "icc_type_id"];

    public function getSearchResult(): SearchResult
    {
        // $url = route('blogPost.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->icc,
            // $url
        );
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal');
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
    // public function distribution()
    // {
    //     return $this->belongsToThrough('App\Distribution','App\Sucursal',null,'',['App\Sucursal' => 'sucursal_id']);
    // }
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
