<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function getRoles(){
        
        $user = Auth::user();

        $notPermmitedRoles = ['administrador','super-admin'];

        if(!$user->hasPermissionTo('create supervisor')){
            array_push($notPermmitedRoles,'supervisor');
        }
        if(!$user->hasPermissionTo('create vendedor')){
            array_push($notPermmitedRoles,'vendedor');
        }
        if(!$user->hasPermissionTo('create externo')){
            array_push($notPermmitedRoles,'externo');
        }

        

        $all_roles_except_a_and_b = Role::whereNotIn('name', $notPermmitedRoles)->get();

        return $all_roles_except_a_and_b;
    }
}
