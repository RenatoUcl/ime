<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'nombre'       => 'required|string|max:255',
            'ap_paterno'   => 'required|string|max:255',
            'ap_materno'   => 'nullable|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email',
            'password'     => 'required|min:8|confirmed',
            'telefono'     => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'    => 'Este correo ya está registrado.',
            'password.min'    => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
