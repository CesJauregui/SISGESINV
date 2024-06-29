<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcesoTitulacionRequest extends FormRequest
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
            'expediente' => 'integer',
            'escuela_profesional' => 'string',
            'titulo_proyecto_tesis' => 'string',
            'numero_oficio' => 'integer',
            'numero_resolucion' => 'integer',
        ];
    }
}
