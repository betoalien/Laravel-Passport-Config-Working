<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginControlador extends Controller
{
    public function login (Request $request) {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $miavatar = $user->avatar;
                $userlevel = $user->userlevel;
                $nombre = $user->name;
                $logincorrecto = "Autorizado";
                $idusuario = $user->id;
                // $response = ['token' => $token];
                // return response($response);
                return response()->json([
                    'token'    => $token,
                    'user'     => $request->email,
                    'userlevel' => $userlevel,
                    'nombre' => $nombre,
                    'logincorrecto' => $logincorrecto,
                    'idusuario' => $idusuario,
                    'miavatar' => $miavatar,
                ]);
            } else {
                return response()->json([
                    'mensaje' => "Contraseña Incorrecta"
                ]);
            }
        } else {
            return response()->json([
                'mensaje' => "Usuario Incorrecto"
            ]);
        }
    }

    public function logout () {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }

}
