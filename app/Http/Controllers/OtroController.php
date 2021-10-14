<?php

namespace App\Http\Controllers;

use App\Otro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtroController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:super-admin|administrador', ['only' => ['update', 'store','destroy']]);
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

    public function getOtros()
    {
        $otros = Otro::all();

        return $otros;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,
        ['nombre'=>'required',
        'descripcion'=>'required',
        'precio'=>'required|numeric',
        'costo'=>'required|numeric',

        ]);
       

        $newOtro = Otro::create([
            'distribution_id' =>  Auth::user()->distribution->id,
            'nombre' => $request['nombre'],
            'descripcion' => $request['descripcion'],
            'precio' => $request['precio'],
            'costo' => $request['costo'],
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Otro  $otro
     * @return \Illuminate\Http\Response
     */
    public function show(Otro $otro)
    {
       'hola';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Otro  $otro
     * @return \Illuminate\Http\Response
     */
    public function edit(Otro $otro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Otro  $otro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Otro $otro)
    {
        $this->validate($request,
        ['nombre'=>'required',
        'descripcion'=>'required',
        'precio'=>'required|numeric',
        'costo'=>'required|numeric',

        ]);
        $otro->update($request->all());
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
