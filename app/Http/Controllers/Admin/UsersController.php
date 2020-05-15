<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sucursal;
use App\Distribution;
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

        if ($request->ajax()) {
            $userDistribution = Auth::User()->distribution()->id;

            $distribution = Distribution::find($userDistribution);

            $users = $distribution->users()->get();

            return UserResource::collection($users);
        } else {
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
        // $roles = Role::all();

        // $sucursales = Sucursal::all();

        // return view("admin.users.create")->with([


        //     'roles' => $roles,
        //     'sucursales' => $sucursales


        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->sucursal_id = $request->sucursal_id;
        $user->role_id = $request->role_id;
        $user->save();

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
        // $user = User::findOrFail($id);

        // $roles = Role::all();

        // $sucursales = Sucursal::all();

        // return view("admin.users.edit", compact("user"))->with([


        //     'roles' => $roles,
        //     'sucursales' => $sucursales


        // ]);   

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
        $message = '';

        $variant = '';

        $title = '';

        if($user->role_id == 1){
            $message = "No es posible eliminar administradores";
            $variant = "danger";
            $title = 'Error';
        }else{
            $user->delete();
            $message = "$user->name Eliminado con exito";
            $variant = "warning";
            $title = 'Exito';
        
        }
        
        return ['message'=>$message,'variant' => $variant,'title'=>$title];
        
    }
}
