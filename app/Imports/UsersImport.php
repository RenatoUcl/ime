<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation
{
    /**
     * Convierte cada fila del CSV en un modelo User.
     */
    public function model(array $row)
    {
        return new User([
            'email'      => $row['email'],
            'password'   => Hash::make($row['password']), 
            'nombre'     => $row['nombre'],
            'ap_paterno' => $row['ap_paterno'],
            'ap_materno' => $row['ap_materno'] ?? null,
            'telefono'   => $row['telefono'] ?? null,
            'estado'     => isset($row['estado']) ? (int) $row['estado'] : 1,
        ]);
    }

    /**
     * Reglas de validación para cada registro del CSV
     */
    public function rules(): array
    {
        return [
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'nombre'     => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'telefono'   => 'nullable|max:20',
            'estado'     => 'required|boolean', // Debe ser 1 o 0 (o true/false)
        ];
    }

    /**
     * Mensajes de error personalizados para las validaciones
     */
    public function customValidationMessages()
    {
        return [
            'email.required'     => 'El campo email es obligatorio.',
            'email.unique'       => 'El correo ya se encuentra registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'nombre.required'    => 'El nombre es obligatorio.',
            'ap_paterno.required'=> 'El apellido paterno es obligatorio.',
            'estado.boolean'     => 'El estado debe ser 1 (activo) o 0 (inactivo).',
        ];
    }

    /**
     * Procesa la inserción en lotes de 1,000 registros para mayor eficiencia
     */
    public function batchSize(): int
    {
        return 100;
    }
}