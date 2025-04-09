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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'email' => 'required|string',
			'nombre' => 'required|string',
			'ap_paterno' => 'required|string',
			'ap_materno' => 'required|string',
			'telefono' => 'required|string',
            'password' => 'required|min:6',
			'estado' => 'required',
        ];
    }
}
