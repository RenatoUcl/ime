<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NivelesTerciarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $nivelId = $this->route('nivelesTerciario') ?? $this->route('id');
        if ($nivelId) {
            $nivel = \App\Models\NivelesTerciario::find($nivelId);
            return $nivel && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $nivel);
        }
        return $this->user()->can('create', \App\Models\NivelesTerciario::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_nivel_secundario' => 'required',
			'id_rol' => 'required',
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
			'estado' => 'required',
        ];
    }
}
