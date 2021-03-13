<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;

use Spatie\Searchable\SearchResult;

class Recarga extends Model implements Searchable
{
    protected $fillable = [
        'name', 'monto','codigo','company_id','taecel_code'
    ];

    public function getSearchResult(): SearchResult
    {
       // $url = route('blogPost.show', $this->slug);
    
        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->name,
           // $url
        );
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }
}
