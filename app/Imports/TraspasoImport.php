<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Spatie\Searchable\Search;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use App\Imei;
use App\Icc;

class TraspasoImport implements ToCollection
{
    use Importable, SkipsFailures;

    private $errores = [];

    private $exitosos = [];

    public function collection(Collection $collection)
    {
        $rows = $collection->toArray();

        $user = Auth::user();

        $inventariosIds = $user->getInventariosForUserIds();

        foreach ($rows as  $row) {

            $searchResult = (new Search())
                ->registerModel(Icc::class, function ($modelSearchAspect) use ($inventariosIds) {
                    $modelSearchAspect


                        ->addExactSearchableAttribute('icc')
                        ->whereHas('inventario', function ($query) use ($inventariosIds) {
                            
                            $query->whereIn('inventario_id', $inventariosIds);
                        })

                        ->otherCurrentStatus(['Vendido', 'Traslado'])


                        ->with(['inventario.inventarioable', 'company', 'type']);
                })
                ->registerModel(Imei::class, function ($modelSearchAspect)  use ($inventariosIds) {
                    $modelSearchAspect


                        ->addExactSearchableAttribute('imei')
                        ->whereHas('inventario', function ($query) use ($inventariosIds) {
                            $query->whereIn('inventario_id', $inventariosIds);
                        })
                        ->otherCurrentStatus(['Vendido', 'Traslado'])


                        ->with(['inventario.inventarioable', 'equipo']);
                })

                ->search(substr($row[0], 0, 19));

                if(sizeof($searchResult) > 0){
                    array_push($this->exitosos, $searchResult);
                }else{
                    $row[0] != null ? array_push($this->errores, $row[0]) : '';
                    
                }

            
        }
        return $searchResult;
    }
    public function getErrors()
    {
        return $this->errores;
    }
    public function getsuccess()
    {
        return $this->exitosos;
    }
}
