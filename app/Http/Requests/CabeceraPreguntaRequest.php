<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CabeceraPreguntaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $cabeceraId = $this->route('cabeceraPregunta') ?? $this->route('id');
        if ($cabeceraId) {
            $cabecera = \App\Models\CabeceraPregunta::find($cabeceraId);
            return $cabecera && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $cabecera);
        }
        return $this->user()->can('create', \App\Models\CabeceraPregunta::class);
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
			'tipo' => 'required',
			'pregunta' => 'required|string',
			'estado' => 'required',
        ];
    }
}
