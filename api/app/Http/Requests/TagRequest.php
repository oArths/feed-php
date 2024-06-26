<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TagRequest extends FormRequest
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
            'tag' => 'required|string|min:5|max:20|unique:tags,name'
        ];
    }
    public function messeges(){
        return [
            'tag.required' => 'É necessario informar a tag',
            'tag.min' => 'A Tag tem que ter no minimo 5 caracteres',
            'tag.max' => 'A Tag tem que ter no maximo 20 caracteres',
            'tag.unique' => 'Essa Tag ja existe',
        ];

        
    }
    protected function failedValidation(Validator $validator){

        throw new HttpResponseException(response()->json([
            'messege' => 'valid validation',
            'error'=> $validator->errors(),
        ], 422));
        
        
    }
}
