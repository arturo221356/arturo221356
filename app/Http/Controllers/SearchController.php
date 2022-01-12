<?php

namespace App\Http\Controllers;

use App\Icc;

use App\Imei;

use App\Recarga;

use Illuminate\Http\Request;

use Spatie\Searchable\Search;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

class SearchController extends Controller
{

    public function ventaPrediction(Request $request)
    {


        // falta hacer los filtros para la distribucion y de la sucursal 

        $searchResults = (new Search())
            ->registerModel(Icc::class, function ($modelSearchAspect) {


                $modelSearchAspect
                    ->limit(5)
                    ->addSearchableAttribute('icc')
                    ->whereHas('inventario', function ($query) {
                        $user = Auth::user();
                        $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                        $query->whereIn('inventario_id', $inventariosIds);
                    })
                    ;
            })
            ->registerModel(Imei::class, function ($modelSearchAspect) {
                $modelSearchAspect
                    ->limit(5)
                    ->addSearchableAttribute('imei')
                    ->whereHas('inventario', function ($query) {
                        $user = Auth::user();
                        $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                        $query->whereIn('inventario_id', $inventariosIds);
                    })
                    ;
            })



            ->search($request->search);


        $respuestas = $searchResults->filter(function ($item) {
            return $item->searchable->status != 'Vendido';
        });
        return $respuestas;
    }

    public function ventaExact(Request $request)
    {

        $searchValue = $request->search;


        if (!Str::is('recar*', $request->search) || !Str::is('paque*', $request->search)) {

            $searchValue = substr($request->search, 0, 19);
        };


        // falta hacer los filtros para la distribucion y de la sucursal 

        $searchResults = (new Search())
            ->registerModel(Icc::class, function ($modelSearchAspect) {


                $modelSearchAspect
                    ->limit(5)
                    ->addExactSearchableAttribute('icc')
                    ->whereHas('inventario', function ($query) {

                        $user = Auth::user();

                        $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                        $query->whereIn('inventario_id', $inventariosIds);
                    })->with(['linea', 'inventario.inventarioable', 'type', 'company'])
                    ->otherCurrentStatus(['Vendido', 'Traslado']);
            })
            ->registerModel(Imei::class, function ($modelSearchAspect) {
                $modelSearchAspect
                    ->limit(5)
                    ->addExactSearchableAttribute('imei')
                    ->whereHas('inventario', function ($query) {
                        $user = Auth::user();

                        $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                        $query->whereIn('inventario_id', $inventariosIds);
                    })->with(['equipo', 'inventario.inventarioable'])
                    ->otherCurrentStatus(['Vendido', 'Traslado']);
            })


            ->search($searchValue);


        return $searchResults;
    }


    public function traspasoPrediction(Request $request)
    {

        $user = Auth::user();

        $inventariosIds = $user->getInventariosForUserIds();


        $searchResults = (new Search())
            ->registerModel(Icc::class, function ($modelSearchAspect) use ($inventariosIds) {

                $modelSearchAspect

                    ->limit(5)
                    ->addSearchableAttribute('icc')
                    ->whereIn('inventario_id', $inventariosIds);
            })
            ->registerModel(Imei::class, function ($modelSearchAspect) use ($inventariosIds) {

                $modelSearchAspect
                    ->otherCurrentStatus(['Vendido', 'Traslado'])
                    ->limit(5)
                    ->addSearchableAttribute('imei')
                    ->whereIn('inventario_id', $inventariosIds);
            })

            ->search($request->search);

        $respuestas = $searchResults->filter(function ($item) {
            return $item->searchable->status != 'Vendido';
        });
        return $respuestas;
    }




    public function traspasoExact(Request $request)
    {

        $searchResult = (new Search())
            ->registerModel(Icc::class, function ($modelSearchAspect) {
                $modelSearchAspect


                    ->addExactSearchableAttribute('icc')
                    ->whereHas('inventario', function ($query) {
                        $user = Auth::user();
                        $query->where('distribution_id', $user->distribution->id);
                    })

                    ->otherCurrentStatus(['Vendido', 'Traslado'])


                    ->with(['inventario.inventarioable', 'company', 'type']);
            })
            ->registerModel(Imei::class, function ($modelSearchAspect) {
                $modelSearchAspect


                    ->addExactSearchableAttribute('imei')
                    ->whereHas('inventario', function ($query) {
                        $user = Auth::user();
                        $query->where('distribution_id', $user->distribution->id);
                    })
                    ->otherCurrentStatus(['Vendido', 'Traslado'])


                    ->with(['inventario.inventarioable', 'equipo']);
            })

            ->search(substr($request->search, 0, 19));


        return $searchResult;
    }
}
