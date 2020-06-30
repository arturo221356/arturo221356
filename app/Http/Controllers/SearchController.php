<?php

namespace App\Http\Controllers;

use App\Icc;

use App\Imei;

use App\Recarga;

use Illuminate\Http\Request;

use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function ventaPrediction(Request $request){


        // falta hacer los filtros para la distribucion y de la sucursal 

        $searchResults = (new Search())
        ->registerModel(Icc::class, function ($modelSearchAspect) {
            $modelSearchAspect
               
                ->limit(5)
                ->addSearchableAttribute('icc');
        })
        ->registerModel(Imei::class, function ($modelSearchAspect) {
            $modelSearchAspect
               
                ->limit(5)
                ->addSearchableAttribute('imei');
        })
        ->registerModel(Recarga::class, function ($modelSearchAspect) {
            $modelSearchAspect
               
                ->limit(5)
                ->addSearchableAttribute('codigo');
        })
        ->search($request->search);
        

    return $searchResults;

    }

    public function exactSearch(Request $request){
        
        $searchResult = (new Search())
        ->registerModel(Icc::class, function ($modelSearchAspect) {
            $modelSearchAspect
               
                
                ->addExactSearchableAttribute('icc');
        })
        ->registerModel(Imei::class, function ($modelSearchAspect) {
            $modelSearchAspect
            ->with('equipo')
                
                ->addExactSearchableAttribute('imei');
        })
        ->registerModel(Recarga::class, function ($modelSearchAspect) {
            $modelSearchAspect

                ->addExactSearchableAttribute('codigo');
        })
        ->search($request->search);
        

    return $searchResult;
    }
}