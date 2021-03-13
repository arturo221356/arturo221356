<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\User;

use App\Inventario;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource as UserResource;
use App\Sucursal;
use App\Caja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

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
            $user = Auth::user();

            $userDistribution = $user->distribution;

            if ($user->can('all inventarios')) {

                $users =  User::all();
            } elseif ($user->can('distribution inventarios')) {



                $users =  $userDistribution->users()->whereHas('roles', function ($query) {
                    $query->whereNotIn('name', ['super-admin', 'administrador']);
                })->orderBy('name','asc')->get();
            } else {

                $users = User::whereHas('roles', function ($query) {
                    $query->whereNotIn('name', ['super-admin', 'administrador', 'supervisor']);
                })->whereHas('inventariosAsignados', function ($query) {


                    $user = Auth::user();

                    $inventariosIds =  $user->InventariosAsignados()->pluck('inventarios.id')->toArray();

                    $query->whereIn('id', $inventariosIds);
                })
                ->orderBy('name','asc')->get();
            }


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
        $loggedUser = Auth::user();

        //revisa que el usuarios tenga permisos para crear usuario
        if (!$loggedUser->hasPermissionTo('create user')) {
            return 'no tienes permisos';
        }

        //roles no permitidos para crear usuarios
        $notPermmitedRoles = ['administrador', 'super-admin'];

        // si el usuario NO tiene permisos para crear supervisores agregra supervisores a roles no permitidos
        if (!$loggedUser->hasPermissionTo('create supervisor')) {
            array_push($notPermmitedRoles, 'supervisor');
        }




        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        $user->password = Hash::make($request->password);
        $user->distribution_id = $loggedUser->distribution->id;

        // si el request es  rol no permitido le asigna en automatico el rol de vendedor
        $user->assignRole(in_array($request->role, $notPermmitedRoles) ? 'vendedor' : $request->role);

        $user->save();

        if ($loggedUser->hasPermissionTo('update permissions')) {
            $user->syncPermissions($request->permisos);
        }

        switch($request->role){

            case 'externo':

                $user->inventario_propio = true;

                $user->inventario()->create(['distribution_id' => $user->distribution->id])->usuariosAsignados()->sync([$user->id, $loggedUser->id]);

                $user->save();
            break;

            case 'supervisor':

                $user->inventariosAsignados()->sync($request->inventarios);

                $user->caja()->create(['total' => 0]);
               

            break;
        }

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

        $loggedUser = Auth::user();

        if (!$loggedUser->hasPermissionTo('update user')) {
            return 'error no tienes permiso';
        }

        $user = User::findOrFail($id);

        $user->name = $request->name;

        $user->email = $request->email;

        $user->telefono = $request->telefono;

        $user->save();

        if ($loggedUser->hasPermissionTo('update permissions')) {
            $user->syncPermissions($request->permisos);
        }

        if ($user->getRoleNames()->first() != 'externo') {


            $user->inventariosAsignados()->sync($request->inventarios);
        }
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

            if ($user->role_id == 1) {
                $message = "No es posible eliminar administradores";
                $variant = "danger";
                $title = 'Error';
            } else {
                $user->delete();
                $message = "$user->name Eliminado con exito";
                $variant = "warning";
                $title = 'Exito';
            }

            return ['message' => $message, 'variant' => $variant, 'title' => $title];
    }
}
