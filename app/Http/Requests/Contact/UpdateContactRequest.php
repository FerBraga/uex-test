<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|regex:/^\(?\d{2}\)?\s?\d{4,5}[-]?\d{4}$/',
            'cpf' => 'nullable|string|regex:/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/',
            'city-edit' => 'nullable|string|max:40',
            'number-edit' => 'nullable|string',
            'street-edit' => 'nullable|string|max:255',
            'state-edit' => 'nullable|string|max:20',
            'zipcodedata-edit' => 'nullable|string|regex:/^\d{5}-?\d{3}$/',
            'complementation-edit' => 'nullable|string|max:40',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'O campo Nome não pode ter mais que 255 caracteres.',

            'phone.regex' => 'O formato do Telefone é inválido.',

            'cpf.regex' => 'O formato do CPF é inválido.',

            'city-edit.max' => 'O campo Cidade não pode ter mais que 40 caracteres.',

            'street-edit.max' => 'O campo Rua não pode ter mais que 255 caracteres.',

            'state-edit.max' => 'O campo Estado não pode ter mais que 20 caracteres.',

            'zipcode-edit.regex' => 'O formato do CEP é inválido.',

            'complementation-edit.max' => 'O campo Complemento não pode ter mais que 40 caracteres.',
        ];
    }
}
