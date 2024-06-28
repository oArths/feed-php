<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userId = $this->route('user');

        return [
           'username' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users')->ignore($userId), // Ignora o ID do usuário atual
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId), // Ignora o ID do usuário atual
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'O nome de usuário é obrigatório.',
            'username.string' => 'O nome de usuário deve ser uma string.',
            'username.min' => 'O nome de usuário deve ter pelo menos 3 caracteres.',
            'username.max' => 'O nome de usuário não pode ter mais de 255 caracteres.',
            'username.unique' => 'Este nome de usuário já está em uso.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Por favor, forneça um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'=> 'validation falid',
            'error' => $validator->errors()
        ], 422));
        
    }
}
