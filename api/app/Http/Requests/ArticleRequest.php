<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleRequest extends FormRequest
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
        return [
            // 'user_id'=> 'required|exist:user,id',
            'title' => 'required|string|max:255|min:1',
            'description' => 'required|string|max:255|min:1',
        ];
    }
    public function messages()
    {
        return [
            // 'user_id.required'=> 'Usuario não fornecido',
            // 'user_id.exist'=> 'Usuario não cadastrado',
            'title.required'=> 'titulo não fornecido',
            'title.max'=> 'maximo de caracteres suportado utrapassado',
            'title.min'=> 'minimo de caracteres não fornecidos',
            'description.max'=> 'maximo de caracteres suportado utrapassado',
            'description.min'=> 'minimo de caracteres não fornecidos',
            'description.required'=> 'descrição não fornecida',

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'=> 'validarion Falid',
            'erro' =>$validator->errors()
        ], 422));
        
    }
}
