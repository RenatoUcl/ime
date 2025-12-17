<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

use App\Models\Roles;
use App\Models\User;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = Collection::make([
            [
                'datos' => [
                    'nombre' => 'Renato',
                    'ap_paterno' => 'Soto',
                    'ap_materno' => 'Moya',
                    'email' => 'renato.soto@uchile.cl',
                    'telefono' => '978653895',
                    'password' => '1646Gamero',
                ],
                'rol' => 'admin',
                'permisos' => [1, 2, 3, 4],
            ],
            [
                'datos' => [
                    'nombre' => 'Director',
                    'ap_paterno' => 'director',
                    'ap_materno' => 'director',
                    'email' => 'director@ime.cl',
                    'telefono' => '123456780',
                    'password' => '.Dir.2025.-',
                ],
                'rol' => 'director',
                'permisos' => [1, 2, 3],
            ],
            [
                'datos' => [
                    'nombre' => 'Coordinador',
                    'ap_paterno' => 'coordinador',
                    'ap_materno' => 'coordinador',
                    'email' => 'coordinador@ime.cl',
                    'telefono' => '123456780',
                    'password' => '.Cor.2025.-',
                ],
                'rol' => 'coordinador',
                'permisos' => [1, 2, 3, 4],
            ],
            [
                'datos' => [
                    'nombre' => 'Equipo',
                    'ap_paterno' => 'equipo',
                    'ap_materno' => 'equipo',
                    'email' => 'equipo@ime.cl',
                    'telefono' => '123456780',
                    'password' => '.Equ.2025.-',
                ],
                'rol' => 'equipo',
                'permisos' => [1, 2, 3],
            ],
        ]);

        $roles = Roles::whereIn('nombre', $usuarios->pluck('rol'))
            ->get()
            ->keyBy('nombre');

        $usuarios->each(function ($usuario) use ($roles) {
            $rol = $roles->get($usuario['rol']);

            if (! $rol) {
                throw new \RuntimeException("No se encontró el rol {$usuario['rol']} para crear el usuario correspondiente.");
            }

            $datos = $usuario['datos'];

            $user = User::updateOrCreate(
                ['email' => $datos['email']],
                [
                    'nombre' => $datos['nombre'],
                    'ap_paterno' => $datos['ap_paterno'],
                    'ap_materno' => $datos['ap_materno'],
                    'telefono' => $datos['telefono'],
                    'password' => Hash::make($datos['password']),
                ],
            );

            $user->roles()->syncWithoutDetaching([$rol->id]);
            $rol->permissions()->sync($usuario['permisos']);
        });
    }
}
