<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegistroRequest;
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
        $this->authorize('create', User::class);
        return view("auth/registro");
    }

    public function registrar(RegistroRequest $request){
        $user = User::create([
            'nombre'     => $request->nombre,
            'ap_paterno' => $request->ap_paterno,
            'ap_materno' => $request->ap_materno,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'telefono'   => $request->telefono,
        ]);

        return to_route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function validar(Request $request){
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credenciales = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credenciales)) {
            $usuario = Auth::user();

            if ((int) $usuario->estado === 0) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta se encuentra desactivada. Contacta al administrador.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ])->onlyInput('email');
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
