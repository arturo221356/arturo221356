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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ImeiResource as ImeiResource;
use App\Http\Resources\IccResource as IccResource;


class InventarioController extends Controller
{
    public function index()
    {
        
        

        
    }
    public function Inventario()
    {
       $user = Auth::User();
       $userRole= $user->Rolename();

       
       if($userRole == 'seller'){
       $userSucursal = $user->sucursalId();
      return view('inventario.index',compact('userRole','userSucursal'));
       }
       else{

        return view('inventario.index',compact('userRole')); 
       
        }
    }
    
    
    
    public function Sims()
    {
        $imeis = Imei::all();
        return view('inventario.index',compact('imeis'));
    }

    

    
    public function getimeis(Request $request){

        $sucursal = $request->sucursal_id;

        $statusArray = $request->status;
 
        
         
        
 
                 if($sucursal == 'all'){
 
                     return ImeiResource::collection(Imei::whereIn('status_id',$statusArray)->get());
 
                 }else{
                     
                     return ImeiResource::collection(Imei::where('sucursal_id','=', $sucursal)->whereIn('status_id',$statusArray)->get());
 
                 }
                     
 
                    
 
    }

    public function geticcs(Request $request){

        $sucursal = $request->sucursal_id;

        $statusArray = $request->status;
 
        
         
        
 
                 if($sucursal == 'all'){
 
                     return IccResource::collection(Icc::whereIn('status_id',$statusArray)->get());
 
                 }else{
                     
                     return IccResource::collection(Icc::where('sucursal_id','=', $sucursal)->whereIn('status_id',$statusArray)->get());
 
                 }
                     
 
                    
 
    }


    







    public function exportImei(Request $request) 
{
    $sucursal = $request->sucursal_id;

    $status = $request->status_id;
    
    return Excel::download(new ImeiExport ($sucursal,$status), 'invoices.xlsx');
 

}

public function exportIcc(Request $request) 
{
    $sucursal = $request->sucursal_id;

    $status = $request->status_id;
    
    return Excel::download(new IccExport ($sucursal,$status), 'invoices.xlsx');
 

}



}
