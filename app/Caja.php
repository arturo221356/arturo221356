<?php

namespace App;

use Hamcrest\Type\IsString;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $appends = ['lastcorte'];

    protected $fillable = ["total"];

    public function cajable()
    {
        return $this->morphTo();
    }
    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    public function cortes()
    {
        return $this->hasMany(Corte::class);
    }
    public function getLastcorteAttribute()
    {
        if($this->cortes()->latest()->first() !== null){
        
            return $this->cortes()->latest()->first();

        }else{
            
            return $this->created_at;
        }
        
       
    }
    public function ventas() {
        return $this->cajable;
            
    }

    public function scopeCajasForUser($query)
    {
        $user = Auth::user();

        if ($user->can('distribution inventarios')) {

            $query->whereHasMorph('cajable',
                [User::class,Inventario::class],
                function ($query) {
                    $user = Auth::user();
                   
                    $query->where([['distribution_id','=', $user->distribution_id],['id','!=',$user->caja->id]]);
                });
        } else {
            return 
            
            $query->whereHasMorph(
                    'cajable',
                    [Inventario::class],
                    function ($query) {
                        $user = Auth::user();
                        $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
                        $query->whereIn('id', $inventariosIds);
                    }
                )
                
                ;
        }
    }
}
