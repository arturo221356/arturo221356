<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sucursal;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource as UserResource;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$sucursales = Sucursal::all();
       
        $userDistribution = Auth::User()->distribution->id;
        if($request->ajax()){
            $users = User::all();
            return UserResource::collection(User::where('distribution_id','=',$userDistribution)->get());
        }else{
            return view('admin.users.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        $sucursales = Sucursal::all();
        
        return view("admin.users.create")->with([
            
          
            'roles' => $roles,
            'sucursales' => $sucursales


        ]);   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        //$this->validate($request,['nombre_sucursal'=>'required','direccion_sucursal'=>'required','email_sucursal'=>'required',]);
        
        $admin = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
          ]);
          $userRole = Role::where('id',$request['rol'])->first();
          $userSucursal = Sucursal::where('id',$request['sucursal'])->first();

          $admin->roles()->attach($userRole);
          $admin->sucursal()->attach($userSucursal);

          return redirect("/admin/users");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $roles = Role::all();

        $sucursales = Sucursal::all();
      
        return view("admin.users.edit", compact("user"))->with([
            
          
            'roles' => $roles,
            'sucursales' => $sucursales


        ]);   
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // $this->validate($request,['nombre_sucursal'=>'required','direccion_sucursal'=>'required','email_sucursal'=>'required',]);
        $user = User::findOrFail($id);
        $user->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $user->delete();
      
    }
}
