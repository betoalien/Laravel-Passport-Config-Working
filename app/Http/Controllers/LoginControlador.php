<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Reports;

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
                $email = $user->email;
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
                    'email' => $email
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


    public function registerUser(Request $request)
    {
        $userlevel = "citizen";
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'mensaje' => "Usuario ya existe"
            ]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->userlevel = $userlevel;
            $user->save();
            return response()->json([
                'mensaje' => "Usuario creado"
            ]);
        }
    }


    public function reportAssign(Request $request)
    {
        $idusuario = $request->idusuario;
        $email = $request->email;
        $latitud = $request->latitud;
        $longitud = $request->longitud;
        $direccion = $request->direccion;
        $descripcion = $request->descripcion;
        $imagen = $request->imagen;
        $fecha = date("Y-m-d H:i:s");
        $status = "Pending";


        $report = new Reports();
        $report->id_user = $idusuario;
        $report->email = $email;
        $report->latitud = $latitud;
        $report->longitud = $longitud;
        $report->direccion = $direccion;
        $report->descripcion = $descripcion;
        $report->imagen = $imagen;
        $report->fecha = $fecha;
        $report->status = $status;
        $report->save();

        return response()->json([
            'message' => "Reporte creado"
        ]);

    }

    public function userReports($id_user){
        $reports = Reports::where('id_user', $id_user)->get();
        return $reports;
    }

}
