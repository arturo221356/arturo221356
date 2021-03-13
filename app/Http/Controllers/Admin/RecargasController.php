<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Recarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Distribution;
use App\Http\Resources\RecargaResource as RecargaResource;

class RecargasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        // if ($request->ajax()) {

            if($request->param){

                $recargas = Recarga::where('company_id',$request->param)->orderBy('id','asc')->get();
            }else{

                $recargas = Recarga::all();
            }
            

        //     return response()->json($recargas);

        // } else {
        //     return view('admin.productos.recargas.index');
        // }

        

        return response()->json($recargas);
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
        $this->validate($request,['name'=>'required','monto'=>'required|max:9999|integer', 'codigo' => 'required|min:5']);
        $recarga=new Recarga;
        $recarga->codigo = $request->codigo;
        $recarga->name = $request->name;
        $recarga->monto = $request->monto;
        $recarga->company_id = $request->company_id;
        $recarga->save();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function show(Recarga $recarga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function edit(Recarga $recarga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recarga $recarga)
    {
        $this->validate($request,['name'=>'required','monto'=>'required|max:9999|integer', 'codigo' => 'required|min:5']);
        
        $recarga->update($request->all());

        $message = '';

        $variant = '';

        $title = '';

        $message = "$recarga->name Editado con exito";
        
        $variant = "success";
        
        $title = 'Exito';

        return ['message'=>$message,'variant' => $variant,'title'=>$title];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recarga  $recarga
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recarga $recarga)
    {
        $recarga->delete();

        $message = '';

        $variant = '';

        $title = '';
            
        $message = "$recarga->name Eliminado con exito";
        
        $variant = "warning";
        
        $title = 'Exito';

        return ['message'=>$message,'variant' => $variant,'title'=>$title];
    }

}
