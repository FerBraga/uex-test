<?php

namespace App\Http\Requests\Contact;

use App\Rules\ValidCep;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;

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
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^\(?\d{2}\)?\s?\d{4,5}[-]?\d{4}$/'],
            'CPF' => ['nullable', new ValidCpf],
            'city' => ['nullable', 'string', 'max:40'],
            'street' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:20'],
            'zipcode' => ['nullable', 'string', new ValidCep],
            'complementation' => ['nullable', 'string', 'max:40'],
        ];
    }
}
