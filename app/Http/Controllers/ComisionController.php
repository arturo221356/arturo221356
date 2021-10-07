<?php

namespace App\Http\Controllers;

use App\Comision;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ComisionesMovistarImport;
use App\Imports\ComisionesTelcelImport;

class ComisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.comisiones');
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

        set_time_limit(0);

        if ($request->hasFile('movistar')) {

            foreach ($request->movistar as $file) {

                $import = new ComisionesMovistarImport;

                $import->onlySheets('MESN', 'MESN-1', 'MESN-2', 'MESN-3', 'MESN-4', 'MESN-5', 'MESN-6');

                Excel::import($import, $file);
            }
        }


        if ($request->hasFile('telcel')) {

            foreach ($request->telcel as $file) {

                $import = new ComisionesTelcelImport;

                Excel::import($import, $file);
            }
        }



        return redirect()->back()->with('message', 'IT WORKS!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function show(Comision $comision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function edit(Comision $comision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comision $comision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comision  $comision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comision $comision)
    {
        //
    }
}
