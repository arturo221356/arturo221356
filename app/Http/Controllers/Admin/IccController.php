<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Icc;
use App\Imei;
// use App\Sucursal;
// use App\Icc_status;
use Illuminate\Http\Request;

class IccController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $iccs = Icc::all();
       
       

        return view('admin.index')->with('iccs',$iccs);
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
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function show(Icc $icc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function edit(Icc $icc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Icc $icc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Icc $icc)
    {
        //
    }
}
