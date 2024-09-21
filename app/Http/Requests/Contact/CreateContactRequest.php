<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;


class CreateContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\(?\d{2}\)?\s?\d{4,5}[-]?\d{4}$/',
            'cpf' => 'required|string|regex:/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/',
            'city' => 'required|string|max:40',
            'number' => 'required|string',
            'street' => 'required|string|max:255',
            'state' => 'required|string|max:20',
            'zipcode' => 'required|string|regex:/^\d{5}-?\d{3}$/',
            'complementation' => 'nullable|string|max:40',
        ];
    }
          /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo Nome é obrigatório.',
            'name.max' => 'O campo Nome não pode ter mais que 255 caracteres.',

            'phone.required' => 'O campo Telefone é obrigatório.',
            'phone.regex' => 'O formato do Telefone é inválido.',

            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.regex' => 'O formato do CPF é inválido.',

            'city.required' => 'O campo Cidade é obrigatório.',
            'city.max' => 'O campo Cidade não pode ter mais que 40 caracteres.',

            'street.required' => 'O campo Rua é obrigatório.',
            'street.max' => 'O campo Rua não pode ter mais que 255 caracteres.',

            'state.required' => 'O campo Estado é obrigatório.',
            'state.max' => 'O campo Estado não pode ter mais que 20 caracteres.',

            'zipcode.required' => 'O campo CEP é obrigatório.',
            'zipcode.regex' => 'O formato do CEP é inválido.',

            'complementation.max' => 'O campo Complemento não pode ter mais que 40 caracteres.',
        ];
    }
}