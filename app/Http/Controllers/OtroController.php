<?php

namespace App\Http\Controllers;

use App\Otro;
use App\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Venta;
use App\SoldOtro;
class OtroController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:super-admin|administrador', ['only' => ['update', 'store', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('otros.index');
    }
    public function reporte()
    {
        return view('otros.reporte');
    }
    

    public function checkStock(Request $request)
    {   
        $user = Auth::user();

        $inventario =  $user->inventariosAsignados()->first();

        $otro = Otro::where('id',$request['id'])->first();

        $response = $otro->checkStock($inventario->id,$request['counter']);

        return $response;
        
    }

    public function getOtros(Request $request)
    {
        $user = Auth::user();

        if ($request['param'] &&  $request['param'] == 'filtrado') {
            $otros = Otro::whereHas('inventarios', function ($query) use ($user) {
                return $query->whereIn('inventario_id', $user->getInventariosForUserIds());
            })->orderBy('name', 'asc')->get();
        } else {

            $otros = Otro::where('distribution_id', $user->distribution_id)->get();
        }
        if ($user->role == 'super-admin') {
            $otros = Otro::all();
        }
        return $otros;
    }

    public function addOtros(Request $request)
    {

        $this->validate(
            $request,
            [
                'inventario_id' => 'required',
                'cantidad' => 'required',
                'accesorio_id' => 'required'
            ]
        );

        $user = Auth::user();

        if (!$user->can('store stock')) {
            return response('No tienes permisos', 401);
        }

        $inventario = Inventario::where('id', $request['inventario_id'])->whereIn('id', $user->getInventariosForUserIds())->first();

        if (!$inventario) {
            return response('No se encontro el inventario', 401);
        }

        $accesorio = Otro::where('id', $request['accesorio_id'])->first();

        $accesorio->addToInventario($inventario->id, $request['cantidad']);

        return response($request['cantidad'] . " " . $accesorio->name . " agregados a " . $inventario->inventarioable->name);
    }

    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
                'name' => 'required',
                'description' => 'required',
                'precio' => 'required|numeric',
                'costo' => 'required|numeric',

            ]
        );


        $newOtro = Otro::create([
            'distribution_id' =>  Auth::user()->distribution->id,
            'name' => $request['name'],
            'description' => $request['description'],
            'precio' => $request['precio'],
            'costo' => $request['costo'],
        ]);
    }

    public function updateInventario(Request $request)
    {
        $user = Auth::user();

        if ($user->can('update stock')) {
            $accesorio = Otro::where('id', $request['id'])->withTrashed()->first();

            if ($request['cantidad'] < 1) {
                $accesorio->inventarios()->detach($request['inventario_id']);
            } else {
                $accesorio->inventarios()->updateExistingPivot(
                    $request['inventario_id'],
                    ['stock' => $request['cantidad']]
                );
            }
        }
    }
    public function deleteInventario(Request $request)
    {
        $user = Auth::user();

        if ($user->can('destroy stock')) {
            $accesorio = Otro::where('id', $request['id'])->withTrashed()->first();

            $accesorio->inventarios()->detach($request['inventario_id']);
        }
    }



    public function update(Request $request, Otro $otro)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'description' => 'required',
                'precio' => 'required|numeric',
                'costo' => 'required|numeric',

            ]
        );
        $otro->update($request->all());
    }

    public function getOtrosVendidos(Request $request)
    {
        $user = Auth::user();

        if ($request->inventario_id == 'all') {
            $inventariosIds = $user->getInventariosForUserIds();
        } else {
            if (in_array($request->inventario_id, $user->getInventariosForUserIds()->toArray())) {
                $inventariosIds = [$request->inventario_id];
            } else {
                $inventariosIds = [];
            }
        }

        $initialDate = Carbon::parse($request['initialDate'])->startOfDay()->toDateTimeString();

        $finalDate = Carbon::parse($request['finalDate'])->endOfDay()->toDateTimeString();
    
        $ventas = Venta::whereIn('inventario_id', $inventariosIds)->whereBetween('created_at', [$initialDate, $finalDate])->with('soldOtros')->orderBy('created_at', 'asc')->get();
    
         $array = [];
        
        foreach($ventas as $venta){
            $imeisIds = $venta->soldOtros()->pluck('id');
            foreach($imeisIds as $id){
                array_push($array, $id);
            }
            
        }
    
        $soldOtro = SoldOtro::whereIn('id',$array)->with(['otro'])->get()->load('venta.inventario.inventarioable');
    
    
    
        return $soldOtro;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Otro  $otro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Otro $otro)
    {
        $otro->delete();
    }
}
