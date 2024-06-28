<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LikeCommentsRequest extends FormRequest
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
            'article_id' => 'required',         
            'user_id' => 'required',         
            'comment_id' => 'required'         
        ];
    }
    public function messages(){
        return [
            'article_id.required' => 'O id do artigo é necessario',
            'user_id.required' => 'O id do usuario é necessario',
            'comment_id.required' => 'O id do comentario é necessario',
        ];
    }

    protected function failedValidation(Validator $validator){

        throw new HttpResponseException(response()->json([
            'message' => 'falid validation',
            'error' => $validator->errors(),
        ], 422));
        
    }
}
