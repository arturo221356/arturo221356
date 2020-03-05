<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imei;
use App\Sucursal;
use App\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ImeiResource as ImeiResource;

class InventarioController extends Controller
{
    public function index()
    {
        
        

        
    }
    public function Equipos()
    {
       $user = Auth::User();
       $userRole= $user->Rolename();

       
       if($userRole == 'seller'){
       $userSucursal = $user->sucursalId();
       $currentSucursal = $user->sucursalName();
        $imeis = Imei::where('sucursal_id',$userSucursal)->get();
        //  return $userSucursal;
      return view('inventario.index',compact('imeis','userRole','currenSucursal'));
       }
       else{
        
        $sucursales = Sucursal::all();
        $imeis = Imei::all();
        $currentSucursal = "Todas";
        //  return $userSucursal;
        return view('inventario.index',compact('imeis','userRole','sucursales','currentSucursal')); 
       }
    }
    
    
    
    public function Sims()
    {
        $imeis = Imei::all();
        return view('inventario.index',compact('imeis'));
    }

    

    
    public function getimeis(Request $request){

        $sucursal = $request->sucursal_id;
 
       
         
        
 
                 if($sucursal == 'all'){
 
                     return ImeiResource::collection(Imei::where('status_id',5)->get());
 
                 }else{
                     
                     return ImeiResource::collection(Imei::where([['sucursal_id','=', $sucursal],['status_id','=',5]])->get());
 
                 }
                     
 
                     //return ImeiResource::collection(Imei::paginate(100));
 
 
    }



}
