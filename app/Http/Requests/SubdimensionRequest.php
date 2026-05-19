<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubdimensionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $subdimensionId = $this->route('subdimension') ?? $this->route('id');
        if ($subdimensionId) {
            $subdimension = \App\Models\Subdimension::find($subdimensionId);
            return $subdimension && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $subdimension);
        }
        return $this->user()->can('create', \App\Models\Subdimension::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_dimension' => 'required',
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
            'posicion' => 'required|integer',
			'estado' => 'required',
        ];
    }
}
