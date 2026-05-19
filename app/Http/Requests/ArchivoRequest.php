<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $archivoId = $this->route('archivo') ?? $this->route('id');
        if ($archivoId) {
            $archivo = \App\Models\Archivo::find($archivoId);
            return $archivo && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $archivo);
        }
        return $this->user()->can('create', \App\Models\Archivo::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_usuario' => 'required',
			'nombre' => 'required|string',
			'archivo' => 'required|string',
			'descripcion' => 'required|string',
        ];
    }
}
