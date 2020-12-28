<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;




class User extends Authenticatable
{

    use SoftDeletes;

    use Notifiable;

    use HasRoles;

    use LaravelPermissionToVueJS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'sucursal_id','telefono'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    



    public function distribution()
    {

        return $this->belongsTo('App\Distribution');
    }

    public function inventario()
    {
        return $this->morphOne('App\Inventario', 'inventarioable');
    }

    public function inventariosAsignados()
    {
        return $this->belongsToMany('App\Inventario');
    }
    public function caja()
    {
        return $this->morphOne(Caja::class, 'cajable');
    }
    

}
