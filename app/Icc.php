<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Searchable\Searchable;

use Spatie\Searchable\SearchResult;

use Spatie\ModelStatus\HasStatuses;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class Icc extends Model implements Searchable
{
    use SoftDeletes;

    use HasStatuses;

    use HasFactory;

    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $dates = ['deleted_at'];

    protected $fillable = ["icc", "inventario_id", "distribution_id", "company_id", "icc_type_id"];

    protected $appends = ['status'];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where($field ?? $this->getRouteKeyName(), $value)->first();
    }

    public function getSearchResult(): SearchResult
    {
        $url = "/icc/".$this->id;

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->icc,
            $url
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
    public function comment()
    {
        return $this->morphOne('App\Comment', 'commentable');
    }
    public function type()
    {
        return $this->belongsTo('App\IccType', 'icc_type_id');
    }
    public function traspasos()
    {
        return $this->morphToMany('App\Traspaso', 'traspasoable');
    }
    public function venta()
    {
        return $this->morphToMany(Venta::class, 'ventaable')->withPivot(['price','cost'])->latest();
    }
    public function linea()
    {
        return $this->hasOne('App\Linea')->withTrashed();
    }

    public function scopeIccInUserDistribution($query, $requestIcc)
    {

        return $query->where('icc', $requestIcc)->with('linea')->whereHas('inventario', function ($query) {
            $user = Auth::user();
            $query->where('distribution_id', $user->distribution->id);
        });
    }
    public function scopeIccInUserInventario($query, $requestIcc)
    {
        return $query->where('icc', $requestIcc)->with('linea')
            ->otherCurrentStatus(['Vendido', 'Traslado'])

            ->whereHas('inventario', function ($query) {
                $user = Auth::user();
                $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
                $query->whereIn('inventario_id', $inventariosIds);
            });
    }
    public static function boot() {
    
        parent::boot();
        
        static::created(function($item){
            $item->setStatus('Disponible');
        });
        
        
        static::deleted(function($item){
            $item->linea ? $item->linea()->delete() : '' ;
            $item->setStatus('Eliminado');
        });

        static::restoring(function ($item) {
            $item->deleteStatus('Eliminado');
            $item->linea ? $item->linea()->restore() : '' ;
        });
    
        
    }
}
