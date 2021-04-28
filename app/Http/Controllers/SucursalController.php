<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Sucursal;
use App\User;
use App\Inventario;
use App\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GrupoResource;

class SucursalController extends Controller
{






    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $user = Auth::user();


            $inventariosIds = $user->getInventariosForUserIds();

            $sucursales = Sucursal::whereHas('inventario', function ($query) use ($inventariosIds) {


                $query->whereIn('id', $inventariosIds);
            })->orderBy('name', 'asc')->get();



            //usa el grupo resource porque basicamente son iguales si se necesita cambiar, crear un nuevo resource
            return GrupoResource::collection($sucursales);
        }
        return view("../admin/sucursales/index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view("../admin/sucursales/create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, ['name' => 'required', 'address' => 'required',]);

        $sucursal = new Sucursal;

        $sucursal->name = $request->name;

        $sucursal->address = $request->address;

        $sucursal->distribution_id = $user->distribution->id;

        $sucursal->save();


        $sucursal->inventario()->create(['distribution_id' => $user->distribution->id])->usuariosAsignados()->attach($user);

        $sucursal->inventario->caja()->create(['total' => 0]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        $this->validate($request, ['name' => 'required', 'address' => 'required',]);
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $sucursal = Sucursal::findOrFail($id);
        // $sucursal->delete();

    }
}
