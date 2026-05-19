<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EncuestasArchivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $archivoId = $this->route('encuestasArchivo') ?? $this->route('id');
        if ($archivoId) {
            $archivo = \App\Models\EncuestasArchivo::find($archivoId);
            return $archivo && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $archivo);
        }
        return $this->user()->can('create', \App\Models\EncuestasArchivo::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_encuesta' => 'required',
			'nombre' => 'required|string',
			'archivo' => 'required|string',
        ];
    }
}
