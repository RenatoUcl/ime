<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegistroRequest;
use App\Models\EncuestasUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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

        $userId = auth()->id();

        // Consulto sobre datos del usuario autenticado
        $encuestas = EncuestasUsuario::query()
            ->with('encuesta:id,nombre') // eager loading
            ->where('id_usuario', $userId)
            ->get()
            ->map(fn ($eu) => [
                'id_encuesta' => $eu->id_encuesta,
                'nombre'      => $eu->encuesta?->nombre,
                'completado'  => $eu->completado,
            ])
            ->unique('id_encuesta')      // simula el DISTINCT
            ->sortBy('id_encuesta')
            ->values();

        $porEncuesta = DB::table('ime_unificada.encuestas as e')
            ->leftJoin('ime_unificada.encuestas_usuarios as eu', 'eu.id_encuesta', '=', 'e.id')
            ->select('e.id', 'e.nombre')
            ->selectRaw('COUNT(DISTINCT eu.id_usuario) as total_asignados')
            ->selectRaw('COUNT(DISTINCT CASE WHEN eu.completado = 1 THEN eu.id_usuario END) as respondieron')
            ->selectRaw('COUNT(DISTINCT CASE WHEN eu.completado = 0 THEN eu.id_usuario END) as pendientes')
            ->where('e.estado', 1)
            ->groupBy('e.id', 'e.nombre')
            ->orderBy('e.id')
            ->get();

            $totales = [
                'total_asignados' => $porEncuesta->sum('total_asignados'),
                'respondieron'    => $porEncuesta->sum('respondieron'),
                'pendientes'      => $porEncuesta->sum('pendientes'),
            ];

        return view('home',compact('encuestas', 'porEncuesta', 'totales'));
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return to_route('login');
    }
}
