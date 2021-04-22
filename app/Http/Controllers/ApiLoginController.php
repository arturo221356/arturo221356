<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    public function loginExterno(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Los datos de inicio de sesion son incorrectos'],
            ]);
        }


        if ($user->hasRole('externo')) {

            $token =  $user->createToken($request->device_name)->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response($response, 201);
        } else {
            return response(['user' => ['Aplicacion solo disponible para usuarios externos']], 403);
        }
    }
    public function logout()
    {

        $user = Auth::user();
        $user->tokens()->delete();
        return response('Loggedout', 200);
    }
}
