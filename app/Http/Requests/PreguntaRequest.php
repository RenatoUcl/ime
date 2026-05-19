<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreguntaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $preguntaId = $this->route('pregunta') ?? $this->route('id');
        if ($preguntaId) {
            $pregunta = \App\Models\Pregunta::find($preguntaId);
            return $pregunta && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $pregunta);
        }
        return $this->user()->can('create', \App\Models\Pregunta::class);
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
			'id_subdimension' => 'required',
			'texto' => 'required|string',
            'tipo' => 'string',
            'posicion' => 'integer',
            'id_dependencia' => 'integer',
        ];
    }
}
