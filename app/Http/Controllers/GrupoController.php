<?php

namespace App\Http\Controllers;

use App\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GrupoResource;

class GrupoController extends Controller
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

            $sucursales = Grupo::whereHas('inventario', function ($query) use ($inventariosIds) {


                $query->whereIn('id', $inventariosIds);
            })->orderBy('name', 'asc')->get();




            return GrupoResource::collection($sucursales);
        }
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
        $user = Auth::user();

        $this->validate($request, ['name' => 'required', 'address' => 'required',]);

        $grupo = new Grupo;

        $grupo->name = $request->name;

        $grupo->address = $request->address;

        $grupo->distribution_id = $user->distribution->id;

        $grupo->save();

        $grupo->syncPermissions($request->permisos);


        $grupo->inventario()->create(['distribution_id' => $user->distribution->id])->usuariosAsignados()->attach($user);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function show(Grupo $grupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function edit(Grupo $grupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required', 'address' => 'required',]);
        $grupo = Grupo::findOrFail($id);
        $grupo->update($request->all());
        $grupo->syncPermissions($request->permisos);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grupo $grupo)
    {
        //
    }
}
