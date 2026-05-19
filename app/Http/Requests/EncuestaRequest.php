<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EncuestaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $encuestaId = $this->route('encuesta') ?? $this->route('id');
        if ($encuestaId) {
            $encuesta = \App\Models\Encuesta::find($encuestaId);
            return $encuesta && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $encuesta);
        }
        return $this->user()->can('create', \App\Models\Encuesta::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_linea' => 'required',
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
			'estado' => 'required'
        ];
    }

}
