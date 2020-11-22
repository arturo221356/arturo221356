<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Sucursal;
use App\User;
use App\Inventario;
use App\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            $userDistribution = $user->distribution;

            if ($user->can('all inventarios')) {

                $sucursales =  Sucursal::all();
            } elseif ($user->can('distribution inventarios')) {



                $sucursales =  $userDistribution->sucursales()->get();
            } else {

                $sucursales = Sucursal::whereHas('inventario', function ($query) {


                    $user = Auth::user();

                    $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                    $query->whereIn('id', $inventariosIds);

                })->get();
            }





            return $sucursales;
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
        return view("../admin/sucursales/create");
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
