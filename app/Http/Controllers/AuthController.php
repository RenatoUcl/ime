<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index(){
        return view("auth/login");
    }

    public function registro(){
        return view("auth/registro");
    }
    public function registrar(Request $request){
        $item = new User();
        $item->nombre = $request->nombre;
        $item->ap_paterno = $request->ap_paterno;
        $item->ap_materno = $request->ap_materno;
        $item->email = $request->email;
        $item->password = Hash::make($request->password);
        $item->telefono = $request->telefono;
        $item->save();
        return to_route('usuarios.index');
    }

    public function validar(Request $request){
        $credenciales = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credenciales)){
            return to_route('home');
        } else {
            return to_route('registro');
        }
    }

    public function home(){
        return view('home');
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return to_route('login');
    }

}
