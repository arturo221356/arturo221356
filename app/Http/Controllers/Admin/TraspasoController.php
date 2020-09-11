<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traspaso;
use App\Imei;
use App\Icc;

class TraspasoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.inventario.traspasos');
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
        $traspaso = Traspaso::create([
            'sucursal_id' => $request->sucursal_id
        ]);

        $items = json_decode($request->data);

        foreach ($items as $item) {
            switch ($item->type) {

                case "iccs":
                    $traspaso->iccs()->attach(
                        $item->id
                    );
                    $icc =  Icc::findorfail($item->id);
                    $icc->sucursal_id = $request->sucursal_id;
                    $icc->save();
                    break;

                case "imeis":
                    $traspaso->imeis()->attach(
                        $item->id
                    );
                    $imei =  Imei::findorfail($item->id);
                    $imei->sucursal_id = $request->sucursal_id;
                    $imei->save();

                    break;
            }
        }



        return $traspaso;
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
