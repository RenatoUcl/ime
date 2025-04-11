<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionRequest extends FormRequest
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
			'institucion' => 'required|string',
			'descripcion' => 'required|string',
			'objetivos' => 'required|string',
			'color_1' => 'required|string',
			'color_2' => 'required|string',
			'color_3' => 'required|string',
			'color_4' => 'required|string',
			'color_5' => 'required|string',
			'color_6' => 'required|string',
			'color_7' => 'required|string',
			'color_8' => 'required|string',
			'color_9' => 'required|string',
			'color_10' => 'required|string',
			'icono' => 'required|string',
			'logo_principal' => 'required|string',
			'logo_secundario' => 'required|string',
			'logo_terciario' => 'required|string',
        ];
    }
}
