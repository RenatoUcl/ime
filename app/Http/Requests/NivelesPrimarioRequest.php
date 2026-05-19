<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NivelesPrimarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $nivelId = $this->route('nivelesPrimario') ?? $this->route('id');
        if ($nivelId) {
            $nivel = \App\Models\NivelesPrimario::find($nivelId);
            return $nivel && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $nivel);
        }
        return $this->user()->can('create', \App\Models\NivelesPrimario::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_linea_programatica' => 'required',
			'id_rol' => 'required',
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
			'estado' => 'required',
        ];
    }
}
