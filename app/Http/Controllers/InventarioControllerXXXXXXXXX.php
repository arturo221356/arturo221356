<?php

namespace App\Http\Controllers;

use App\Exports\ImeiExport;
use App\Exports\IccExport;
use Illuminate\Http\Request;
use App\Imei;
use App\Icc;
use App\Sucursal;
use App\Role;
use App\User;
use App\Inventario;
use App\Distribution;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ImeiResource as ImeiResource;
use App\Http\Resources\IccResource as IccResource;


class InventarioController extends Controller

{
    

      
    
        
   

    public function Index()
    {
        $user = Auth::User();
        $userRole = $user->role->name;


        if ($userRole == 'seller') {
            $userSucursal = $user->sucursal;
            return view('inventario.index', compact('userRole', 'userSucursal'));
        } else {

            return view('inventario.index', compact('userRole'));
        }
    }



    // public function Sims()
    // {
    //     $imeis = Imei::all();
    //     return view('inventario.index', compact('imeis'));
    // }




    public function getimeis(Request $request)
    {

        $sucursal_id = $request->sucursal_id;

        $statusArray = $request->status;

        $userDistribution = Auth::User()->distribution()->id;

        $distribution = Distribution::find($userDistribution);


        if ($sucursal_id == 'all') {
           
            $imeis = $distribution->imeis()->whereIn('status_id', $statusArray)->get();
    
            return ImeiResource::collection($imeis);

        } else {

            $sucursal = Sucursal::find($sucursal_id);

            $imeis = $sucursal->imeis()->whereIn('status_id',$statusArray)->get();

            return ImeiResource::collection($imeis);

         }
    }










    public function geticcs(Request $request)
    {

        

        $sucursal_id = $request->sucursal_id;

        $statusArray = $request->status;

        $userDistribution = Auth::User()->distribution()->id;

        $distribution = Distribution::find($userDistribution);



        if ($sucursal_id == 'all') {

            $iccs = $distribution->iccs()->whereIn('status_id', $statusArray)->get();
    
            return IccResource::collection($iccs);

        } else {


            $sucursal = Sucursal::find($sucursal_id);

            $iccs = $sucursal->iccs()->whereIn('status_id',$statusArray)->get();

            return IccResource::collection($iccs);
        }

    }










    public function exportImei(Request $request)
    {
        $sucursal = $request->sucursal_id;

        $status = $request->status_id;

        return Excel::download(new ImeiExport($sucursal, $status), 'invoices.xlsx');
    }

    public function exportIcc(Request $request)
    {
        $sucursal = $request->sucursal_id;

        $status = $request->status_id;

        return Excel::download(new IccExport($sucursal, $status), 'invoices.xlsx');
    }
}
