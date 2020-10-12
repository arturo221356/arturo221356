<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\IccProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Distribution;

class IccProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if ($request->ajax()) {

            // $userDistribution = Auth::User()->distribution()->id;

            // $distribution = Distribution::find($userDistribution);
        
            // $productos = $distribution->IccProducts()->get();
            $productos = IccProduct::all();
            return response()->json($productos);

        // } else {
        //     return view('admin.productos.sims.index');
        // }
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
        // $this->validate($request,['name'=>'required']);
        // $product = new IccProduct;
        // $product->name = $request->name;
        // $product->recarga_required = $request->recarga_required;
        // $product->initial_price = $request->initial_price;
        // $product->sim_cost = $request->sim_cost;
        // // $product->recarga_externa = $request->recarga_externa;
        // $product->distribution_id = Auth::user()->distribution()->id;
        // $product->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\IccProduct  $iccProduct
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        

        $product =IccProduct::findOrFail($id)->subproducts;

        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IccProduct  $iccProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(IccProduct $iccProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IccProduct  $iccProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IccProduct $iccProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IccProduct  $iccProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(IccProduct $iccProduct)
    {
        //
    }
    
}


