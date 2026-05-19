<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'email' => 'required|email|unique:users,email',
			'nombre' => 'required|string|max:255',
			'ap_paterno' => 'required|string|max:255',
			'ap_materno' => 'required|string|max:255',
			'telefono' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
			'estado' => 'boolean',
        ];
    }
}
