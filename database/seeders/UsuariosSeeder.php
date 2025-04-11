<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Roles;

use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'nombre' => 'Renato',
            'ap_paterno' => 'Soto',
            'ap_materno' => 'Moya',
            'email' => 'renato.soto@uchile.cl',
            'telefono' => '978653895',
            'password' => Hash::make('1646Gamero')
        ]);
        $director = User::create([
            'nombre' => 'Director',
            'ap_paterno' => 'director',
            'ap_materno' => 'director',
            'email' => 'director@ime.cl',
            'telefono' => '123456780',
            'password' => Hash::make('.Dir.2025.-')
        ]);
        $coordinador = User::create([
            'nombre' => 'Coordinador',
            'ap_paterno' => 'coordinador',
            'ap_materno' => 'coordinador',
            'email' => 'coordinador@ime.cl',
            'telefono' => '123456780',
            'password' => Hash::make('.Cor.2025.-')
        ]);
        $equipo = User::create([
            'nombre' => 'Equipo',
            'ap_paterno' => 'equipo',
            'ap_materno' => 'equipo',
            'email' => 'equipo@ime.cl',
            'telefono' => '123456780',
            'password' => Hash::make('.Equ.2025.-')
        ]);

        // Asignar roles
        $admin->roles()->attach(Roles::where('nombre', 'admin')->first()->id);
        $director->roles()->attach(Roles::where('nombre', 'director')->first()->id);
        $coordinador->roles()->attach(Roles::where('nombre', 'coordinador')->first()->id);
        $equipo->roles()->attach(Roles::where('nombre', 'equipo')->first()->id);

        // Asignar permisos al rol admin
        $adminRole = Roles::where('nombre', 'admin')->first();
        $adminRole->permissions()->sync([1, 2, 3, 4]);

        // Asignar permisos al rol Director
        $directorRole = Roles::where('nombre', 'director')->first();
        $directorRole->permissions()->sync([1, 2, 3]);

        // Asignar permisos al rol Coordinador
        $coordinadorRole = Roles::where('nombre', 'coordinador')->first();
        $coordinadorRole->permissions()->sync([1, 2, 3, 4]);

        // Asignar permisos al rol Equipo
        $equipoRole = Roles::where('nombre', 'equipo')->first();
        $equipoRole->permissions()->sync([1, 2, 3]);
    }
}
