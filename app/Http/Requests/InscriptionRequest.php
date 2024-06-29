<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscriptionRequest extends FormRequest
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
            'file' => [$sometimes, 'integer'],
            'professional_school' => [$sometimes, 'string', 'max:255'],
            'thesis_project_title' => [$sometimes, 'string', 'max:255'],
            'office_number' => 'nullable|string',
            'resolution_number' => 'nullable|string',
            'reception_date_faculty' => [$sometimes, 'date'],
            'approval_date_udi' => [$sometimes, 'date'],
            'description' => [$sometimes, 'string'],
            'archives.*' => [$sometimes],
            'user_id' => [$sometimes, 'integer', 'exists:users,id'],
            'user_ids' => [$sometimes, 'array'],
            'user_ids.*' => 'exists:users,id',
        ];
    }
}
