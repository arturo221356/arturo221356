<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Inventario;
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
        // if ($request->ajax()) {

            $user = Auth::user();

           if( $user->can('cargar inventarios')){
            return view('inventario.index');
           }else{
               return 'no puto no ';
           }

            // $userDistribution = $user->distribution;

        
               
            //         $inventarios =  Inventario::where('distribution_id', $userDistribution->id)->get();

            //         $response = json_encode(InventarioResource::collection($inventarios));


            // }
            // $inventarios = Inventario::all();

            
        
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

        $userDistribution = $user->distribution;

        $producto = $request->producto;

   

        if ($id == 'all') {
            if ($producto === 'Imei') {
                $imeis = $userDistribution->imeis()->otherCurrentStatus('Vendido')->get();
                 $response = ImeiResource::collection($imeis);
                return $response;
            }
            else if ($producto === 'Icc') {
                $iccs = $userDistribution->iccs()->otherCurrentStatus('Vendido')->get();
                $response = IccResource::collection($iccs);
                return $response;
            }
        } else {
            $inventario = Inventario::find($id);
           
             if($inventario->distribution->id != $userDistribution->id){
                 return 'error';
             }


            if ($producto === 'Imei') {
                $imeis = $inventario->imeis()->otherCurrentStatus('Vendido')->get();
                $response = ImeiResource::collection($imeis);
                return $response;
            } else if ($producto === 'Icc') {
                $iccs = $inventario->iccs()->otherCurrentStatus('Vendido')->get();
                $response = IccResource::collection($iccs);
                return $response;
            }
        }
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
