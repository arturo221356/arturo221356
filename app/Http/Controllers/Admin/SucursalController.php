<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sucursal;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SucursalController extends Controller
{

    
    
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sucursales= Sucursal::all();
        return view("../admin/sucursales/index",compact("sucursales"));
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
        $this->validate($request,['name'=>'required','address'=>'required','email'=>'required',]);
        $sucursal=new Sucursal;
        $sucursal->name=$request->nombre_sucursal;
        $sucursal->address=$request->direccion_sucursal;
        $sucursal->email=$request->email_sucursal;
        $sucursal->save();
        return redirect("/admin/sucursales");
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
        $sucursal = Sucursal::findOrFail($id);
        return view("admin.sucursales.edit", compact("sucursal"));   
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
        $this->validate($request,['name'=>'required','address'=>'required','email'=>'required',]);
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update($request->all());
        return redirect("/admin/sucursales");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->delete();
        return redirect("/admin/sucursales");
    }
    public function getSucursales(){

        $userDistribution = Auth::User()->distribution->id;

        $data = Sucursal::where('distribution_id','=',$userDistribution)->get();
   
        return response()->json($data);
    }
}
