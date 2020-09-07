<?php

namespace App\Http\Controllers;

use App\IccType;
use Illuminate\Http\Request;

class IccTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $iccTypes = null;

        if($request->param){
            $iccTypes = IccType::where('company_Id','=',$request->param)->get();
        }

       

        return $iccTypes;
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
     * @param  \App\IccType  $iccType
     * @return \Illuminate\Http\Response
     */
    public function show(IccType $iccType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IccType  $iccType
     * @return \Illuminate\Http\Response
     */
    public function edit(IccType $iccType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IccType  $iccType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IccType $iccType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IccType  $iccType
     * @return \Illuminate\Http\Response
     */
    public function destroy(IccType $iccType)
    {
        //
    }
}
