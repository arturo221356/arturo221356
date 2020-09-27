<?php

namespace App\Http\Controllers;

use App\Linea;

use App\Icc;

use App\Chip;

use App\Recarga;

use App\Transaction;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LineaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function verificarIcc(Request $request)
    {


        $requestIcc = $request->icc;

        $user = Auth::user();


        if ($user->can('distribution inventarios')) {

            $icc = Icc::iccInUserDistribution($requestIcc)->with('company','type')->first();
        } else {
            $icc = Icc::iccInUserInventario($requestIcc)->with('company','type')->first();
        }

        if ($icc->linea()->first() == null) {
            $response = [
                "success" => true,
                "data" => $icc,
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Icc ya tiene linea activa",
                "data" => $icc->with('linea', 'linea.product')->first(),
            ];
        }

        return  json_encode($response);
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
    public function preactivarPrepago(Request $request)
    {
        

        $user = Auth::user();

        if($user->can('preactivar linea'))
        
        $requestIcc = $request->icc;

        $dn = $request->dn;

        if ($user->can('distribution inventarios')) {

            $icc = Icc::iccInUserDistribution($requestIcc)->first();
        } else {
            $icc = Icc::iccInUserInventario($requestIcc)->first();
        }
        if($icc->linea()->first() != null){
            return $icc->linea()->first();
        }


        $chip = Chip::create([

        ]);
        $linea = $chip->linea()->create([
            'dn' => $dn,
            'icc_product_id' => 1,
            'icc_id' => $icc->id,

        ]);
        $linea->setStatus('Preactivo');

        $response = "hola";

        return $response;
    }






    public function activaChip(Request $request){

        $recarga = Recarga::find(1);

        $dn = $request->dn;

        $linea = Linea::where('dn',$dn);

        if($linea == null){
            return 'ni madres';
        }
    
        $res = Http::asForm()->post('https://taecel.com/app/api/RequestTXN', [
            'key' => 'c490127ff864a719bd89877f32a574de',
            'nip' => '0c4ae19986107edd5ebcec3c6e08a0d0',
            'producto'=> $recarga->taecel_code,
            'referencia' => $dn
        ]);
        $response = json_decode($res);
    
        $trasnsaction = new Transaction;
    
        $trasnsaction->taecel = true;
    
        $trasnsaction->taecel_success = $response->success;
    
        if($response->data){
            $trasnsaction->taecel_transID = $response->data->transID;
        }
        
    
        $trasnsaction->taecel_message =  $response->message;
    
        $trasnsaction->monto = $recarga->monto;
    
        $trasnsaction->dn = $dn;
    
        $trasnsaction->company_id = $recarga->company_id;
    
        $trasnsaction->recarga_id = $recarga->id;
    
        $trasnsaction->inventario_id = 2;
    
        $trasnsaction->save();
    
    
    
        return $trasnsaction;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function show(Linea $linea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function edit(Linea $linea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Linea $linea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Linea  $linea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Linea $linea)
    {
        //
    }
}
