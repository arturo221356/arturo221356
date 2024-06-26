<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\ModelStatus\HasStatuses as HasStatuses;

use Illuminate\Support\Carbon;

use App\Jobs\ChecksItx;

use Spatie\Searchable\Searchable;

use Spatie\Searchable\SearchResult;

use Illuminate\Support\Facades\Http;





class Linea extends Model  implements Searchable
{


    use HasStatuses;

    use SoftDeletes;

    protected $appends = ['status', 'reason', 'producto','subproducto','monto_recarga','preactivated_at','activated_at'];


    protected $fillable = ["icc_id", "dn", "icc_product_id", "icc_sub_product_id"];

    public function getSearchResult(): SearchResult
    {
        $url = "/icc/".$this->icc->id;

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->icc,
            $url
        );
    }

    public function icc()
    {
        return $this->belongsTo('App\Icc')->withTrashed();
    }
    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
    public function productoable()
    {
        return $this->morphTo();
    }
    public function comisiones()
    {
        return $this->morphOne(Comision::class, 'comisionable');
    }


    public function product()
    {
        return $this->belongsTo('App\IccProduct', 'icc_product_id');
    }
    public function subProduct()
    {
        return $this->belongsTo('App\IccSubProduct', 'icc_sub_product_id');
    }

    public function getProductoAttribute()
    {
        return $this->product->name ?? null;
    }
    public function getSubproductoAttribute()
    {
        return $this->subProduct->name ?? null;
    }
    public function getActivatedAtAttribute()
    {
        return $this->productoable->activated_at  ?  Carbon::parse($this->productoable->activated_at)->format('d/m/y H:i:s' ) : null;
    }
    public function getPreactivatedAtAttribute()
    {
        return $this->productoable->preactivated_at  ?  Carbon::parse($this->productoable->preactivated_at)->format('d/m/y H:i:s' ) : null;
    }
    public function getMontoRecargaAttribute()
    {
        return $this->productoable->transaction->monto  ?? null;
    }
    public function getReasonAttribute()
    {
        return $this->latestStatus()->reason;
    }
    public function checkItx($numero)
    {
        $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/movistar/getITX', [
            'linea' => $numero,

        ]);


        $response = json_decode(substr($consulta, 4));

        return $response;
    }
    public function checkCompany($numero)
    {

        $consulta = Http::asForm()->post('http://promoviles.herokuapp.com/api/movistar/getCarrier', [
            'linea' => $numero,

        ]);

        $response = json_decode(substr($consulta, 4));

        return $response;
    }





    public function newLineaWithProduct($producto, $iccId)
    {
        switch ($producto->iccProduct->id) {

                //en caso de que la request sea linea nueva prepago 
            case 1:
                //crea un nuevo chip
                $chip = Chip::create([
                    'preactivated_at' => now()
                ]);
                $status = 'Preactiva';
                break;
                //en caso de que la request sea portabilidad 
            case 2:

                if (isset($producto->porta->fvc)) {

                    $stringFvc = Carbon::parse($producto->porta->fvc)->toIso8601String();

                    $fvc = new Carbon($stringFvc);

                    $fvc->hour = 9;
                }

                $fvc =
                    // crea un nuevo chip
                    $chip = Porta::create([

                        'nip' => isset($producto->porta->nip) ? $producto->porta->nip : 1234,
                        'temporal' => isset($producto->porta->temporal) ? $producto->porta->temporal : null,
                        'trafico' => isset($producto->porta->trafico) ? $producto->porta->trafico : null,
                        'fvc' => isset($fvc) ? $fvc : null,

                    ]);

                $status = 'Porta subida';

                ChecksItx::dispatch($chip);

                break;

            case 3:

                // crea un nuevo pospago
                $chip = Pospago::create([

                    'preactivated_at' => now(),
                    'activated_at' => now(),

                ]);
                $status = 'Pospago';
                break;

            case 4:

                // crea una nueva linea remplazo

                $chip = Remplazo::create([

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $status = 'Remplazo';
                break;
            case 5:

                // crea una nueva linea telemarketing
                $chip = Telemarketing::create([


                    'preactivated_at' => now(),
                    'activated_at' => now(),
                ]);
                $status = 'Telemarketing';

                break;
        }

        // y le asigna una linea con el dn de la request 
        $linea = $chip->linea()->create([
            'dn' => (string)$producto->dn,
            'icc_product_id' => $producto->iccProduct->id,
            'icc_sub_product_id' => isset($producto->iccSubProduct->id) ? $producto->iccSubProduct->id : null,
            'icc_id' => $iccId,

        ]);

        $linea->setStatus($status);




        return $linea;
    }
}
