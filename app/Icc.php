<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Searchable\Searchable;

use Spatie\Searchable\SearchResult;

use Spatie\ModelStatus\HasStatuses;



class Icc extends Model implements Searchable
{
    use SoftDeletes;

    use HasStatuses;

    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $dates = ['deleted_at'];

    protected $fillable = ["icc", "inventario_id","distribution_id", "company_id", "icc_type_id"];

    protected $appends = ['status'];

    public function getSearchResult(): SearchResult
    {
        // $url = route('blogPost.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->icc,
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
    public function linea()
    {
        return $this->hasOne('App\Linea');
    }
}
