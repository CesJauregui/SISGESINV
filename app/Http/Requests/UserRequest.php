<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $sometimes = '';
        if ($this->getMethod() == 'POST') {
            $sometimes = 'required';
        } else if ($this->getMethod() == 'PUT') {
            $sometimes = 'sometimes';
        }
        return [
            'rol' => [$sometimes, 'string'],
            'nombre' => [$sometimes, 'string'],
            'apellidos' => [$sometimes, 'string'],
            'email' => [$sometimes, 'string'],
            'password' => ['sometimes', 'string'],
            'celular' => [$sometimes, 'integer'],
            'codigo' => [$sometimes, 'integer'],
            'fecha_egreso' => ['nullable'],
            'carrera' => ['nullable', 'string'],
            'linea' => ['nullable', 'string'],
            'sub_lineas' => ['nullable', 'string'],
            'es_revisor' => ['sometimes', 'boolean'],
            'es_asesor' => ['sometimes', 'boolean'],
            'es_jurado' => ['sometimes', 'boolean'],
        ];
    }
}
