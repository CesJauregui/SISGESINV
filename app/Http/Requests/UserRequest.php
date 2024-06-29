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
            'role' => [$sometimes, 'string'],
            'name' => [$sometimes, 'string'],
            'surnames' => [$sometimes, 'string'],
            'email' => [$sometimes, 'string'],
            'password' => ['sometimes', 'string'],
            'phone' => [$sometimes, 'integer'],
            'code' => [$sometimes, 'integer'],
            'discharge_date' => ['nullable', 'date'],
            'cycle' => ['nullable', 'string'],
            'career' => ['nullable', 'string'],
            'line' => ['nullable', 'string'],
            'sublines' => ['nullable', 'string'],
            'is_reviewer' => ['sometimes', 'boolean'],
            'is_advisor' => ['sometimes', 'boolean'],
            'is_jury' => ['sometimes', 'boolean'],
            'orcid' => ['sometimes', 'string'],
            'cip' => ['sometimes', 'integer']
        ];
    }
}
