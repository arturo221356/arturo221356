<?php

namespace App\Http\Controllers;

use App\Icc;

use App\Imei;

use App\Recarga;

use Illuminate\Http\Request;

use Spatie\Searchable\Search;

use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    
    public function ventaPrediction(Request $request){


        // falta hacer los filtros para la distribucion y de la sucursal 
        

        $searchResults = (new Search())
        ->registerModel(Icc::class, function ($modelSearchAspect) {
            $modelSearchAspect
                
                ->limit(5)
                ->addSearchableAttribute('icc')
                ->where('status_id', '!=', 5)
                ;
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

    public function ventaExact(Request $request){
        
        $searchResult = (new Search())
        ->registerModel(Icc::class, function ($modelSearchAspect) {
            $modelSearchAspect
               
                
                ->addExactSearchableAttribute('icc')
                ->where('status_id', '!=', 5);
        })
        ->registerModel(Imei::class, function ($modelSearchAspect) {
            $modelSearchAspect
            ->with('equipo')
                
                ->addExactSearchableAttribute('imei')
                ->where('status_id', '!=', 5);
        })
        ->registerModel(Recarga::class, function ($modelSearchAspect) {
            $modelSearchAspect

                ->addExactSearchableAttribute('codigo');
        })
        ->search($request->search);
        

    return $searchResult;
    }



    public function traspasoPrediction(Request $request){

        
        // falta hacer los filtros para la distribucion y de la sucursal 

        $searchResults = (new Search())
        ->registerModel(Icc::class, function ($modelSearchAspect) {
            
            
            $modelSearchAspect
                ->limit(5)
                ->addSearchableAttribute('icc')
                ->otherCurrentStatus(['Vendido', 'Traslado'])
                ->whereHas('inventario', function($query) {
                    $user = Auth::user();
                    $query->where('distribution_id', $user->distribution->id);
                })
                ;
        })
        ->registerModel(Imei::class, function ($modelSearchAspect) {
            $modelSearchAspect
                ->limit(5)
                ->addSearchableAttribute('imei')
                ->otherCurrentStatus(['Vendido', 'Traslado'])
                
                ->whereHas('inventario', function($query) {
                    $user = Auth::user();
                    $query->where('distribution_id', $user->distribution->id);
                })
                ;
        })
        
        ->search($request->search);
        

    return $searchResults;

    }




    public function traspasoExact(Request $request){
        
        $searchResult = (new Search())
        ->registerModel(Icc::class, function ($modelSearchAspect) {
            $modelSearchAspect
               
              
                ->addExactSearchableAttribute('icc')

                ->otherCurrentStatus(['Vendido', 'Traslado'])
                ->whereHas('inventario', function($query) {
                    $user = Auth::user();
                    $query->where('distribution_id', $user->distribution->id);
                })
                
                ->with(['inventario.inventarioable','company','type']);
        })
        ->registerModel(Imei::class, function ($modelSearchAspect) {
            $modelSearchAspect
           
                
                ->addExactSearchableAttribute('imei')
                ->otherCurrentStatus(['Vendido', 'Traslado'])
                ->whereHas('inventario', function($query) {
                    $user = Auth::user();
                    $query->where('distribution_id', $user->distribution->id);
                })
                
                ->with(['inventario.inventarioable','equipo']);
                
        })

        ->search($request->search);
        

    return $searchResult;
    }

}
