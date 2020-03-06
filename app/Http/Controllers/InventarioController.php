<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imei;
use App\Sucursal;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ImeiResource as ImeiResource;

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
                     
 
                     //return ImeiResource::collection(Imei::paginate(100));
 
 
    }



}
