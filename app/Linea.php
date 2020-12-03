<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

use Illuminate\Support\Facades\Auth;

class Linea extends Model
{


    use HasStatuses;

    use SoftDeletes;

    protected $appends = ['status', 'reason'];


    protected $fillable = ["icc_id", "dn", "icc_product_id", "icc_sub_product_id"];

    public function icc()
    {
        return $this->belongsTo('App\Icc');
    }
    public function productoable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo('App\IccProduct', 'icc_product_id');
    }
    public function subProduct()
    {
        return $this->belongsTo('App\IccSubProduct', 'icc_sub_product_id');
    }

    public function getReasonAttribute()
    {
        return $this->latestStatus()->reason;
    }



    public function scopeDistributionExportadas($linea, $initialDate, $finalDate)
    {
        $lineas = $linea

            ->whereBetween('created_at', [$initialDate, $finalDate])

            ->currentStatus('Exportada')

            ->whereHas('icc.inventario', function ($query) {
                $user = Auth::user();
                $query->where('distribution_id', $user->distribution->id);
            })
            ->orderBy('created_at', 'asc')->get();

        return $lineas;
    }


    public function scopeInUserInventarioExportadas($linea, $initialDate, $finalDate)
    {
        $lineas = $linea

            ->whereBetween('created_at', [$initialDate, $finalDate])

            ->currentStatus('Exportada')

            ->whereHas('icc.inventario', function ($query) {
                $user = Auth::user();
                $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();
                $query->whereIn('inventario_id', $inventariosIds);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return $lineas;
    }

    public function scopeInventarioPortas($linea, $initialDate, $finalDate, $inventario_id)
    {


        $lineas = $linea


            ->whereBetween('created_at', [$initialDate, $finalDate])

            ->currentStatus('Exportada')


            ->whereHas('icc', function ($query) use ($inventario_id) {
                $query->where('inventario_id', $inventario_id);
            })
            ->orderBy('created_at', 'asc')

            ->get();

        return $lineas;
    }
}
