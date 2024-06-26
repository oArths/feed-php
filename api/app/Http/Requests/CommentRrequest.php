<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRrequest extends FormRequest
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
            'article_id' => 'required|exists:articles,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|max:100|min:1',
        ];
    }
    public function messages()
    {
        return [
            'article_id.required' => 'Id do artigo não fornecido',
            'article_id.exists' => 'artigo não existe',
            'user_id.required' => 'Id do usuario não fornecido',
            'user_id.exists' => 'artigo não existe',
            'content.required' => 'Conteudo não fornecido',
            'content.max' => 'Conteudo utrapassa limite',
            'content.min' => 'Conteudo insuficiente',

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'falid validation',
            'erro' => $validator->errors()
        ], 422));
        
    }
}
