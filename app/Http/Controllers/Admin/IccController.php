<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Icc;
use App\IccProduct;
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
        $iccs = Icc::find(1)->get();

        $products = IccProduct::all();
       
       

        return view('admin.index', compact("iccs","products"));
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
    public function update(Request $request, $id)
    {
        $icc = Icc::findorfail($id);


        $icc->update($request->all());
        
        
        if ($request->comment !=NULL) {
            
            $icc->comment()->updateOrCreate([],['comment' => $request->comment]);

        } else {
            
            $icc->comment()->delete();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Icc  $icc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Icc $icc)
    {
        $icc->delete();
    }
}
