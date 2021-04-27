<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Inventario;
use App\Imei;
use App\Icc;
use App\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\InventarioCollection;
use App\Http\Resources\InventarioResource;
use App\Http\Resources\ImeiResource;
use App\Http\Resources\IccResource;

class InventarioController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // $user = Auth::user();

            // $userDistribution = $user->distribution;

            // if ($user->can('all inventarios')) {

            //     $inventarios =  Inventario::all();

            // } elseif ($user->can('distribution inventarios')) {



            //     $inventarios =  $userDistribution->inventarios()->get()->sortBy(function ($batch) { 
            //         return $batch->inventarioable->name; 
            //    });

            // } else {

            //     $inventarios =    $user->inventariosAsignados()->get()->sortBy(function ($batch) { 
            //         return $batch->inventarioable->name; 
            //    });

            // }

            $inventarios = Auth::user()->getInventariosForUser();

            if ($request->param) {
                $response = json_encode(InventarioResource::collection($inventarios->where('inventarioable_type', $request->param)));
            } else {
                $response = json_encode(InventarioResource::collection($inventarios));
            }



            return $response;
        }
        return view('inventario.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = Auth::user();

        if ($id == 'all') {
            $inventariosIds = $user->getInventariosForUserIds();
        } else {
            if (in_array($id, $user->getInventariosForUserIds()->toArray())) {
                $inventariosIds = [$id];
            } else {
                $inventariosIds = [];
            }
        }


        $onlyTrash = json_decode($request->onlyTrash);

        switch ($request->producto) {


            case 'Imei':
                if ($onlyTrash == true) {
                    $imeis = Imei::whereIn('inventario_id', $inventariosIds)->onlyTrashed()->otherCurrentStatus('Vendido')->get();
                } else {
                    $imeis = Imei::whereIn('inventario_id', $inventariosIds)->otherCurrentStatus('Vendido')->get();
                }


                $response = ImeiResource::collection($imeis);
                break;
            case 'Icc':

                if ($onlyTrash == true) {
                    $iccs = Icc::whereIn('inventario_id',$inventariosIds)->onlyTrashed()->otherCurrentStatus('Vendido')->get();
                }else{
                    $iccs = Icc::whereIn('inventario_id',$inventariosIds)->otherCurrentStatus('Vendido')->get();
                }

                $response = IccResource::collection($iccs);
                break;
        }
        return $response;


        // $user = Auth::user();

        // $userDistribution = $user->distribution;

        // $producto = $request->producto;

        // $onlyTrash = json_decode($request->onlyTrash);






        // if ($id == 'all') {

        //     switch ($producto) {


        //         case 'Imei':



        //             if ($user->can('distribution inventarios')) {

        //                 if ($onlyTrash == true) {
        //                     $imeis = $userDistribution->imeis()->onlyTrashed()->get();
        //                 }else if ($onlyTrash == false) {
        //                     $imeis = $userDistribution->imeis()->otherCurrentStatus('Vendido')->get();
        //                 }





        //                 $response = ImeiResource::collection($imeis);
        //             } else {


        //                 $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();


        //                 if ($onlyTrash == true) {

        //                     $imeis = Imei::whereIn('inventario_id', $inventariosIds)->onlyTrashed()->get();

        //                 }else if ($onlyTrash == false){
        //                     $imeis = Imei::whereIn('inventario_id', $inventariosIds)->otherCurrentStatus('Vendido')->get();
        //                 }
        //                 $response = ImeiResource::collection($imeis);
        //             }


        //             break;

        //         case 'Icc':


        //             if ($user->can('distribution inventarios')) {


        //                 if ($onlyTrash == true) {
        //                     $iccs = $userDistribution->iccs()->onlyTrashed()->get();
        //                 } else if ($onlyTrash == false) {
        //                     $iccs = $userDistribution->iccs()
        //                         ->otherCurrentStatus('Vendido')
        //                         ->get();
        //                 }




        //                 $response = IccResource::collection($iccs);
        //             } else {

        //                 $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

        //                 if ($onlyTrash == true) {

        //                     $iccs = Icc::whereIn('inventario_id', $inventariosIds)
        //                         ->onlyTrashed()
        //                         ->get();
        //                 } else if ($onlyTrash == false) {

        //                     $iccs = Icc::whereIn('inventario_id', $inventariosIds)
        //                         ->otherCurrentStatus('Vendido')
        //                         ->get();
        //                 }







        //                 $response = IccResource::collection($iccs);
        //             }

        //             break;
        //     }
        //     return $response;
        // } else {

        //     $inventario = Inventario::find($id);


        //     if ($producto === 'Imei') {

        //         if ($onlyTrash == true) {

        //             $imeis = $inventario->imeis()->onlyTrashed()->get();

        //         }else if ($onlyTrash == false) {

        //             $imeis = $inventario->imeis()->otherCurrentStatus('Vendido')->get();
        //         }



        //         $response = ImeiResource::collection($imeis);

        //         return $response;
        //     } else if ($producto === 'Icc') {
        //         if ($onlyTrash == true) {
        //             $iccs = $inventario->iccs()
        //                 ->onlyTrashed()
        //                 ->get();
        //         } else if ($onlyTrash == false) {
        //             $iccs = $inventario->iccs()
        //                 ->otherCurrentStatus('Vendido')
        //                 ->get();
        //         }
        //         $response = IccResource::collection($iccs);
        //         return $response;
        //     }
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
