<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imei;
use App\Sucursal;
use App\Role;
use Illuminate\Support\Facades\Auth;

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



}
