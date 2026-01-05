<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'     => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'nullable|string|max:255',
            'email'      => 'required|email',
            'telefono'   => 'nullable|string|max:20',
            'password'   => 'nullable|confirmed|min:6',
        ];
    }
}