<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\InventarioCollection;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    //    if($request->ajax()){
        
        $user = Auth::user();

        $userRoleName = $user->role->name;
        
        $userDistribution = $user->distribution();

        switch($userRoleName){
            case 'admin':
               $inventarios =  Inventario::where('distribution_id',$userDistribution->id)->get();
               
               $response = json_encode(InventarioCollection::collection($inventarios));
               
               return   $response ;
            break;
            case 'supervisor':

            break;
            case 'vendedor':

            break;
            case 'cambaceo':

            break;
            case 'externo':

            break;
        }
        $inventarios = Inventario::all();
        
        return $userDistribution;
    //    }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
